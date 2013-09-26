(function ($) {
  $.fn.get_selection = function () {
  var e = this.get(0);
  if('selectionStart' in e) { //Mozilla and DOM 3.0
	return { start: e.selectionStart, 
			 end: e.selectionEnd, 
			 length: e.selectionEnd - e.selectionStart, 
			 text: e.value.substring(e.selectionStart, e.selectionEnd) };
  }
  else if(document.selection) { //IE
	e.focus();
	var r = document.selection.createRange();
	var tr = e.createTextRange();
	if (r == null || tr == null) return { start: e.value.length, end: e.value.length, length: 0, text: '' };
	var tr2 = tr.duplicate();
	tr2.moveToBookmark(r.getBookmark());
	tr.setEndPoint('EndToStart',tr2);
	var text_part = r.text.replace(/[\r\n]/g,'.'); //for some reason IE doesn't always count the \n and \r in length
	var text_whole = e.value.replace(/[\r\n]/g,'.');
	var the_start = text_whole.indexOf(text_part,tr.text.length);
	return { start: the_start, end: the_start + text_part.length, length: text_part.length, text: r.text };
  }
  else //Browser not supported
	return { start: e.value.length, end: e.value.length, length: 0, text: '' };
  };

  $.fn.set_selection = function (start_pos,end_pos) {
  var e = this.get(0);
  if('selectionStart' in e) { //Mozilla and DOM 3.0
	e.focus();
	e.selectionStart = start_pos;
	e.selectionEnd = end_pos;
  }
  else if (document.selection) { //IE
	e.focus();
	var tr = e.createTextRange();

	//Fix IE from counting the newline characters as two seperate characters
	var stop_it = start_pos;
	for (i=0; i < stop_it; i++) if( e.value[i].search(/[\r\n]/) != -1 ) start_pos = start_pos - .5;
	stop_it = end_pos;
	for (i=0; i < stop_it; i++) if( e.value[i].search(/[\r\n]/) != -1 ) end_pos = end_pos - .5;

	tr.moveEnd('textedit',-1);
	tr.moveStart('character',start_pos);
	tr.moveEnd('character',end_pos - start_pos);
	tr.select();
  }
  return this.get_selection();
  };
}(jQuery));

/*
Before going further, you should be aware of the alternate approach called "editable iframe" [keywords: designMode, execCommand, WYSIWYG] [see also: http://www.quirksmode.org/dom/execCommand.html]
Disclaimer: The following was a result of complete ignorance! The use and/or study of this is considered unhealthy...
*/
function update_preview(src, str) {
  if (typeof str == "string") {
	$(src).nextAll(".view").html(str);
	return;
  }
  src.value = src.value.replace(/<(?!\/|([biusqp]|br ?\/?|sup|sub|ul|ol|li|table|tr|td|th( style=['"][^'"]+['"])?|a href=['"][^'"]+['"]|center|img [^>]+)>)/g, "&lt;");
  src.value = src.value.replace(/<\/(?!([biusqpa]|sup|sub|ul|ol|li|center|table|tr|td|th)>)/g, "&lt;/");
  $(src).nextAll(".view").html(src.value);
}
function get_corrected_selection(src) {
  var sel = $(src).get_selection();
  var invalidable1 = /^[^<]*>/.exec(sel.text), invalidable2 = /<[^>]*$/.exec(sel.text);
  var new_start = sel.start+(invalidable1 == null ? 0 : invalidable1[0].length);
  var new_end = sel.end-(invalidable2 == null ? 0 : invalidable2[0].length);
  if (new_start != sel.start || new_end != sel.end) {
	$(src).set_selection(new_start, new_end);
	sel = $(src).get_selection();
  }
  return sel;
}
function valid_selection_ends(src, sel) {
  var invalidable1 = /<[^>]*$/.test(src.value.substring(sel.start-8, sel.start)), 
	invalidable2 = /^[^<]*>/.test(src.value.substring(sel.end, sel.end+8));
  return !(invalidable1 || invalidable2);
}
function styler(src, tag) {
  var tag_o = "<"+tag+">", tag_c = "</"+tag+">";
  var sel = get_corrected_selection(src);
  if (sel.length == 0)
	if (!valid_selection_ends(src, sel))
	  return;
  var start_pos = sel.start;
  var end_pos;
  var tag_o_i = sel.text.indexOf(tag_o), tag_c_i = sel.text.indexOf(tag_c), tag_c_li = sel.text.lastIndexOf(tag_c);
  if (tag_o_i == 0 && tag_c_li > 0 && tag_c_li == sel.length-tag_c.length) { //<tag>...</tag>
	end_pos = sel.end - tag_o.length - tag_c.length;
	src.value = src.value.substring(0, sel.start) + sel.text.substring(tag_o.length, sel.length-tag_c.length) + src.value.substring(sel.end);
  } else if (tag_o_i >= 0 && tag_c_i > tag_o_i && tag != "q") { //...<tag>...</tag>...
	end_pos = sel.end;
	src.value = src.value.substring(0, sel.start) + tag_o + sel.text.replace(tag_o, "").replace(tag_c, "") + tag_c + src.value.substring(sel.end);
  } else if (tag_o_i >= 0 && tag_c_i >= 0 && tag_c_i < tag_o_i) { //...</tag>...<tag>...
	end_pos = sel.end - tag_o.length - tag_c.length;
	src.value = src.value.substring(0, sel.start) + sel.text.replace(tag_o, "").replace(tag_c, "") + src.value.substring(sel.end);
  } else if (tag_o_i == 0 && tag_c_i < 0) { //<tag>...
	end_pos = sel.end;
	src.value = src.value.substring(0, sel.start) + sel.text.replace(tag_o, "") + tag_o + src.value.substring(sel.end);
  } else if (tag_o_i > 0 && tag_c_i < 0) { //...<tag>...
	end_pos = sel.end;
	src.value = src.value.substring(0, sel.start) + tag_o + sel.text.replace(tag_o, "") + src.value.substring(sel.end);
  } else if (tag_o_i < 0 && tag_c_i >= 0 && tag_c_i == sel.length-tag_c.length) { //...</tag>
	end_pos = sel.end;
	src.value = src.value.substring(0, sel.start) + tag_c + sel.text.replace(tag_c, "") + src.value.substring(sel.end);
  } else if (tag_o_i < 0 && tag_c_i >= 0 && tag_c_i < sel.length-tag_c.length) { //...</tag>...
	end_pos = sel.end;
	src.value = src.value.substring(0, sel.start) + sel.text.replace(tag_c, "") + tag_c + src.value.substring(sel.end);
  } else { //...
	src.value = src.value.substring(0, sel.start) + tag_o + sel.text + tag_c + src.value.substring(sel.end);
	if (sel.length == 0)
	  start_pos = end_pos = sel.start + tag_o.length;
	else
	  end_pos = start_pos + tag_o.length + sel.length + tag_c.length;
  }
  $(src).set_selection(start_pos,end_pos);
  update_preview(src);
}
function styler_click(e) {
  styler($(e.target).closest(".kaja-input").children("textarea").get(0), e.data.tag);
}
function before_state1(state1_i, state2_i) {
  return (state1_i >= 0 && ((state2_i >= 0 && state1_i < state2_i) || state2_i < 0));
}
function after_state1(state1_li, state2_li) {
  return (state1_li >= 0 && ((state2_li >= 0 && state1_li > state2_li) || state2_li < 0));
}
function enter_pressed(src, shifted) {
  if (!shifted)
	return true;
  var sel = $(src).get_selection();
  if (!valid_selection_ends(src, sel)) {
	update_preview(src);
	return false;
  }
  var cursor_pos, new_start, new_end, ex;
  if (/^[^<]*<\/li>/.test(src.value.substring(sel.start))) {
	src.value = src.value.substring(0, sel.start) + "</li>\n<li>" + src.value.substring(sel.start);
	cursor_pos = sel.start+10;
  } else if ( ex=/<\/li>\s*$/.exec(src.value.substring(0,sel.start)) ) {
	src.value = src.value.substring(0, ex.index) + "</li>\n<li>" + src.value.substring(ex.index);
	cursor_pos = ex.index+10;
  } else {
	var li_c_i = src.value.indexOf("</li>", sel.start), li_o_i = src.value.indexOf("<li>", sel.start), //>=0 implies >=sel.start
	  li_c_li = src.value.lastIndexOf("</li>", sel.start-1), li_o_li = src.value.lastIndexOf("<li>", sel.start-1),
	  ul_c_i = src.value.indexOf("</ul>", sel.start), ul_o_i = src.value.indexOf("<ul>", sel.start),
	  ul_c_li = src.value.lastIndexOf("</ul>", sel.start-1), ul_o_li = src.value.lastIndexOf("<ul>", sel.start-1),
	  ol_c_i = src.value.indexOf("</ol>", sel.start), ol_o_i = src.value.indexOf("<ol>", sel.start),
	  ol_c_li = src.value.lastIndexOf("</ol>", sel.start-1), ol_o_li = src.value.lastIndexOf("<ol>", sel.start-1);
	var after_ul_o = after_state1(ul_o_li, ul_c_li), after_ol_o = after_state1(ol_o_li, ol_c_li),
	  before_ul_c = before_state1(ul_c_i, ul_o_i), before_ol_c = before_state1(ol_c_i, ol_o_i);
	//alert("after_ol_o: " + after_ol_o + ", after_ul_o: " + after_ul_o + ", before_ol_c: " + before_ol_c + ", before_ul_c: " + before_ul_c);
	var inside_ul = false, inside_ol = false, ignore = false;
	if (after_ul_o && before_ul_c) {
	  if (!after_ol_o && !before_ol_c)
		inside_ul = true;
	  else if (after_ol_o && before_ol_c) {
		if (ul_o_li > ol_o_li && ul_c_i < ol_c_i)
		  inside_ul = true;
		else if (ol_o_li > ul_o_li && ol_c_i < ul_c_i)
		  inside_ol = true;
		else
		  ignore = true;
	  } else {
		if (after_ol_o && ol_o_li > ul_o_li) {
		  //src.value = src.value.substring(0, ol_o_li+4) + "</ol>" + src.value.substring(ol_o_li+4);
		  new_start = ol_o_li;
		  new_end = new_start+4;
		} else if (before_ol_c && ol_c_i < ul_c_i) {
		  new_start = ol_c_i;
		  new_end = new_start+5;
		}
		ignore = true;
	  }
	} else if (after_ol_o && before_ol_c && !after_ul_o && !before_ul_c)
	  inside_ol = true;
	else if (after_ul_o && ul_o_li > ol_o_li) {
	  //src.value = src.value.substring(0, ul_o_li+4) + "</ul>" + src.value.substring(ul_o_li+4);
	  new_start = ul_o_li;
	  new_end = new_start+4;
	  ignore = true;
	} else if (before_ul_c && ul_c_i < ol_c_i) {
	  new_start = ul_c_i;
	  new_end = new_start+5;
	  ignore = true;
	}
	if (inside_ol || inside_ul) {
	  var par_o_li = (inside_ol ? ol_o_li : ul_o_li), par_c_i = (inside_ol ? ol_c_i : ul_c_i);
	  if (after_state1(li_o_li, li_c_li)) {
		if (before_state1(li_c_i, li_o_i)) { //<li>..<x>..|..</x>..</li>
		  src.value = src.value.substring(0, li_c_i+5) + "\n<li></li>" + src.value.substring(li_c_i+5);
		  cursor_pos = li_c_i+10;
		} else if (before_state1(li_o_i, li_c_i) && par_o_li > li_o_li) { //<li>...<xl>|<li>
		  src.value = src.value.substring(0, par_o_li+4) + "\n<li></li>\n" + src.value.substring(li_o_i);
		  cursor_pos = par_o_li+9;
		} else {
		  new_end = new_start = li_o_li+4;
		  ignore = true;
		}
	  } else if (before_state1(li_o_i, li_c_i)) { //<xl>|<li>
		src.value = src.value.substring(0, par_o_li+4) + "\n<li></li>\n" + src.value.substring(li_o_i);
		cursor_pos = par_o_li+9;
	  } else { //<xl>|</xl>
		src.value = src.value.substring(0, par_o_li+4) + "\n<li></li>\n" + src.value.substring(par_c_i);
		cursor_pos = par_o_li+9;
	  }
	} else if (!ignore) {
	  src.value = src.value.substring(0, sel.start) + "<br/>\n" + src.value.substring(sel.end);
	  cursor_pos = sel.start+6;
	}
  }

  if (ignore) {
	if (!new_start)
	  new_end = new_start = sel.start;
  } else
	new_end = new_start = cursor_pos;
  update_preview(src);
  $(src).set_selection(new_start,new_end);
  return false;
}
function get_line(str, index) {
  var i = str.indexOf("\n", index), li = index > 0 ? str.lastIndexOf("\n", index-1) : -1;
  if (i >= 0) {
	if (li >= 0)
	  return {start: li+1, end: i, text: str.substring(li+1, i)};
	else
	  return {start: 0, end: i, text: str.substring(0, i)};
  } else if (li >= 0)
	return {start: li+1, end: str.length, text: str.substring(li+1)};
  else
	return {start: 0, end: str.length, text: str};
}
function lister(src, tag) {
  var tag_o = "<"+tag+">", tag_c = "</"+tag+">", li_o = "\n<li>", li_c = "</li>\n";
  var sel = get_corrected_selection(src);
  var cursor_pos;
  if (sel.length == 0) {
	var curline = get_line(src.value, sel.start), i1, i2;
	var li_o_i = curline.text.indexOf("<li>"), li_c_i = curline.text.indexOf("</li>");
	if (li_o_i < li_c_i) { //li_c_i >= 0
	  var li_c_li = src.value.lastIndexOf("</li>", curline.start);
	  if (li_c_li > 0 && li_c_li > curline.start-8) {
		i1 = i2 = curline.start;
		i2 += li_c_i;
		src.value = src.value.substring(0, li_c_li) + "\n" + tag_o + "\n" + src.value.substring(i1, i2) + li_c + tag_c + "\n" + src.value.substring(i2);
		cursor_pos = li_c_li + 6 + li_c_i;
	  } else if (li_o_i < 0) {
		i1 = i2 = curline.start;
		i2 += li_c_i;
		src.value = src.value.substring(0, i1) + tag_o + li_o + src.value.substring(i1, i2) + li_c + tag_c + "\n" + src.value.substring(i2);
		cursor_pos = i2 + 9;
	  }
	} else if (li_c_i < 0 && li_o_i < 0 && !(/<\/?[uo]l>/.test(curline.text))) {
	  i1 = curline.start; i2 = curline.end;
	  src.value = src.value.substring(0, i1) + tag_o + li_o + src.value.substring(i1, i2) + li_c + tag_c + src.value.substring(i2);
	  cursor_pos = i2 + 9;
	}
  } else if (/^\s*<li>/.test(sel.text) && /<\/li>\s*$/.test(sel.text.substring(sel.text.lastIndexOf("\n")))) {
	var li_c_li = src.value.substring(sel.start-10, sel.start).lastIndexOf("</li>");
	if (li_c_li >= 0) {
	  li_c_li += sel.start-10;
	  src.value = src.value.substring(0, li_c_li) + "\n" + tag_o + "\n" + sel.text.substring(sel.text.indexOf("<li>"), sel.text.lastIndexOf("</li>")+5) + "\n" + tag_c + "\n</li>" + src.value.substring(sel.end);
	  cursor_pos = li_c_li+5;
	}
  } else if (/<\/?(ol|ul|li)>/.test(sel.text) == false) {
	src.value = src.value.substring(0, sel.start) + tag_o + li_o + sel.text.replace(/\n/g, "</li>\n<li>") + li_c + tag_c + src.value.substring(sel.end);
	cursor_pos = sel.start+4;
  } //TODO else if's: switch ol-ul, smart-delisting!! WTF?!
  if (typeof cursor_pos != "undefined")
	$(src).set_selection(cursor_pos,cursor_pos);
  update_preview(src);
}
function lister_click(e) {
  lister($(this).closest(".kaja-input").children("textarea").get(0), e.data.tag);
}
function help_click() {
  var t = $(this).closest(".kaja-input").children(".help-view");
  if (t.html() == "")
	t.html("Pressing Shift+Enter: <ul><li>Inserts a proper line-break when outside lists (bullets and numbering)</li> <li>Creates a new list-item when inside lists (bounded by 'ul' for bulleted (unordered) and 'ol' for numbered (ordered)).</li></ul>" +
		   "To convert a simple line-seperated list to an ordered/unodered list, select them and press one of the bulleting or numbering buttons shown above...<br/>" +
		   "Finally, feel free to press Ctrl+Z to undo any unexpected changes...<br/><br/><span class=\"clear\"></span>");
  else
	t.html("");
}
function ins_img_click() {
  var ki = $(this).closest(".kaja-input");
  var src = ki.children("textarea").get(0);
  var sel = get_corrected_selection(src);
  if (!valid_selection_ends(src, sel)) {
	update_preview(src);
	return;
  }
  var d = $("<div />").appendTo(ki);
  d.ajaxupload({ /* also adds ax-uploader class to d */
	url:'uploader.php',
	remotePath:'../2012/images/events/',
	maxFiles: 1,
	maxFileSize: '2M',
	allowExt:['jpg','png'],
	maxConnections: 1,
	thumbWidth: 500,
	thumbHeight: 400,
	thumbPostfix: '_resized',
	editFilename: true,
	finish: function(files) {
	  var u = $(this.source), img = files[0], exti = img.lastIndexOf('.');
	  img = img.substring(0, exti) + "_resized" + img.substring(exti);
	  u.ajaxupload('destroy');
	  var src = u.closest(".kaja-input").children("textarea").get(0),
		  sel = $(src).get_selection(),
		  itag = "<img src=\"/2012/images/events/"+img+"\" alt=\""+files[0]+"\"/>";
	  src.value = src.value.substring(0, sel.start) + itag + src.value.substring(sel.end);
	  $(src).set_selection(sel.start,sel.start+itag.length);
	  update_preview(src);
	  u.remove();
	}
  });
  var browse = $("<input type=button value=Browse />")
	.click(function() { $(this).closest(".ax-uploader").find("input.ax-browse").click(); })
	.appendTo(d);
  $("<input type=button value=Upload />")
	.click(function() { $(this).closest(".ax-uploader").ajaxupload('start'); this.disabled=true; })
	.appendTo(d);
  $("<input type=button value=Cancel />")
	.click(function() { $(this).closest(".ax-uploader").ajaxupload('destroy'); })
	.appendTo(d);
  browse.click();
}
function new_kaja_input(src) {
  $(src).wrap("<div class=\"kaja-input\" />");
  $("<span/>", {"class": "ki-but", html: "<b>B</b>", title: "Bold"}).click({tag: "b"}, styler_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "<i>I</i>", title: "Italics"}).click({tag: "i"}, styler_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "<u>U</u>", title: "Underline"}).click({tag: "u"}, styler_click).insertBefore(src);
  //$("<span/>", {"class": "ki-but", html: "<s>S</s>", title: "Strikeout"}).click({tag: "s"}, styler_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "<q>q</q>", title: "Quotes"}).click({tag: "q"}, styler_click).insertBefore(src);
  $("<span/>", {"class": "ki-but sups", html: "X<sup>2</sup>", title: "Superscript"}).click({tag: "sup"}, styler_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "X<sub>2</sub>", title: "Subscript"}).click({tag: "sub"}, styler_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "<img src=\"style/bull.png\" alt=\"Bulleting\" />", title: "Bulleting"}).click({tag: "ul"}, lister_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "<img src=\"style/num.png\" alt=\"Numbering\" />", title: "Numbering"}).click({tag: "ol"}, lister_click).insertBefore(src);
  $("<span/>", {"class": "ki-but", html: "<img src=\"style/img.png\" alt=\"Insert image\" />", title: "Insert image"}).click(ins_img_click).insertBefore(src);
  $("<span/>", {"class": "ki-but long", html: "Preview"}).insertBefore(src);
  $("<span/>", {"class": "ki-but long", html: "Help"}).click(help_click).insertBefore(src);
  $("<span/>", {"class":"clear"}).insertBefore(src);
  $("<span/>", {"class":"help-view"}).insertBefore(src);

  $("<span/>", {"class":"clear"}).insertAfter(src);
  $("<div/>", {"class":"view"}).insertAfter(src);
  $(src).blur(function() {
	update_preview(this);
  });
  $(src).keypress(function(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if (code == 13)
	  return enter_pressed(this, e.shiftKey);
  });
}
