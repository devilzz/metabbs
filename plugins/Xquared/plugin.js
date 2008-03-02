
String.prototype.repeat = function(number) {
	return number < 1 ? '' : this + this.repeat(number - 1);
};

function loadXq() {
	function getAdditionalAutocompletions() {
		return [
			{
				id:'isbn',
				criteria: /@ISBN:\d+$/i,
				handler: function(xed, rdom, block, wrapper, text) {
					var isbn = text.split(":")[1]
					var korean = isbn.indexOf("97889") == 0 || isbn.indexOf("89") == 0
					var href = korean ?
						"http://www.aladdin.co.kr/shop/wproduct.aspx?ISBN=" :
						"http://www.amazon.com/exec/obidos/ISBN="
					var node = rdom.createElement('A');
					node.innerHTML = 'ISBN:' + isbn;
					node.href = href + isbn;
					node.className = 'external';
					node.title = 'ISBN:' + isbn;
					
					wrapper.innerHTML = "";
					wrapper.appendChild(node);
				}
			},
			{
				id:'anchor',
				criteria: /@A(:(.+))?$/i,
				handler: function(xed, rdom, block, wrapper, text) {
					var m = text.match(/@A(:(.+))?$/i);
					var anchorId = m[2] ? m[2] : function() {
						var id = 0;
						while(true) {
							var element = rdom.$("a" + (id));
							if(!element) return "a" + id;
							id++;
						}
					}();
					
					var node = rdom.createElement('A');
					node.id = anchorId;
					node.href = '#' + anchorId;
					node.className = 'anchor';
					node.title = 'Anchor ' + anchorId;
					node.innerHTML = '(' + anchorId + ')';
					
					wrapper.innerHTML = "";
					wrapper.appendChild(node);
				}
			}
		];
	}

	function getAdditionalAutocorrections() {
		return [
			{id:'bullet', criteria: /^(\s|\&nbsp\;)*(\*|-)(\s|\&nbsp\;).+$/, handler: function(xed, rdom, block, text) {
				rdom.pushMarker();
				rdom.removePlaceHoldersAndEmptyNodes(block);
				block.innerHTML = block.innerHTML.replace(/((\s|&nbsp;)*(\*|\-)\s*)/, "");
				if(block.nodeName == "LI") xed.handleIndent();
				if(block.parentNode.nodeName != "UL") xed.handleList('UL');
				rdom.popMarker(true);
			}},
			{id:'numbering', criteria: /^(\s|\&nbsp\;)*(\d\.|#)(\s|\&nbsp\;).+$/, handler: function(xed, rdom, block, text) {
				rdom.pushMarker();
				rdom.removePlaceHoldersAndEmptyNodes(block);
				block.innerHTML = block.innerHTML.replace(/(\s|&nbsp;)*(\d\.|\#)\s*/, "")
				if(block.nodeName == "LI") xed.handleIndent();
				if(block.parentNode.nodeName != "OL") xed.handleList('OL');
				rdom.popMarker(true);
			}},
			{id:'imageUrl', criteria: /https?:\/\/.*?\/(.*?\.(jpg|jpeg|gif|bmp|png))$/i, handler: function(xed, rdom, block, text) {
				var fileName = text.match(/https?:\/\/.*?\/(.*?\.(jpg|jpeg|gif|bmp|png))$/i)[1];
				block.innerHTML = "";
				var img = rdom.createElement("img");
				img.src = text;
				img.alt = fileName;
				img.title = fileName;
				block.appendChild(img);
				rdom.selectElement(block);
				rdom.collapseSelection(false);
			}},
			{id:'separator', criteria: /^----*$/, handler: function(xed, rdom, block, text) {
				if(rdom.tree.isBlockContainer(block)) block = rdom.wrapAllInlineOrTextNodesAs("P", block, true)[0];
				rdom.insertNodeAt(rdom.createElement("HR"), block, "before");
				block.innerHTML = "";
				rdom.placeCaretAtStartOf(block);
				return true;
			}},
			{id:'heading', criteria: /^\=+[^=]*\=+(\&nbsp;)*$/, handler: function(xed, rdom, block, text) {
				var textWithoutEqualMarks = text.strip().replace(/=/g, "");
				var level = Math.min(6, parseInt((text.length - textWithoutEqualMarks.length) / 2))
				xed.handleApplyBlock('H' + level);
				block = rdom.getCurrentBlockElement();
				block.innerHTML = textWithoutEqualMarks;
				rdom.selectElement(block);
				rdom.collapseSelection();
			}}
		];
	}
	
	function getAdditionalShortcuts() {
		if(xq.Browser.isMac) {
			// Mac FF & Safari
			return [
				{event:"Ctrl+Shift+SPACE", handler:"this.handleAutocompletion(); stop = true;"},
				{event:"Ctrl+Meta+0", handler:"xed.handleApplyBlock('P')"},
				{event:"Ctrl+Meta+1", handler:"xed.handleApplyBlock('H1')"},
				{event:"Ctrl+Meta+2", handler:"xed.handleApplyBlock('H2')"},
				{event:"Ctrl+Meta+3", handler:"xed.handleApplyBlock('H3')"},
				{event:"Ctrl+Meta+4", handler:"xed.handleApplyBlock('H4')"},
				{event:"Ctrl+Meta+5", handler:"xed.handleApplyBlock('H5')"},
				{event:"Ctrl+Meta+6", handler:"xed.handleApplyBlock('H6')"},
				
				{event:"Ctrl+Meta+B", handler:"xed.handleApplyBlock('BLOCKQUOTE')"},
				{event:"Ctrl+Meta+D", handler:"xed.handleApplyBlock('DIV')"},
				{event:"Ctrl+Meta+EQUAL", handler:"xed.handleSeparator()"},				
				
				{event:"Ctrl+Meta+O", handler:"xed.handleList('OL')"},
				{event:"Ctrl+Meta+U", handler:"xed.handleList('UL')"},
				
				{event:"Ctrl+Meta+E", handler:"xed.handleRemoveBlock()"},
				
				{event:"Ctrl+(Meta)+COMMA", handler:"xed.handleJustify('left')"},
				{event:"Ctrl+(Meta)+PERIOD", handler:"xed.handleJustify('center')"},
				{event:"Ctrl+(Meta)+SLASH", handler:"xed.handleJustify('right')"},
				
				{event:"Meta+UP", handler:"xed.handleMoveBlock(true)"},
				{event:"Meta+DOWN", handler:"xed.handleMoveBlock(false)"}
			];
		} else if(xq.Browser.isUbuntu) {
			//  Ubunto FF
			return [
				{event:"Ctrl+SPACE", handler:"xed.handleAutocompletion(); stop = true;"},
				{event:"Ctrl+0", handler:"xed.handleApplyBlock('P')"},
				{event:"Ctrl+1", handler:"xed.handleApplyBlock('H1')"},
				{event:"Ctrl+2", handler:"xed.handleApplyBlock('H2')"},
				{event:"Ctrl+3", handler:"xed.handleApplyBlock('H3')"},
				{event:"Ctrl+4", handler:"xed.handleApplyBlock('H4')"},
				{event:"Ctrl+5", handler:"xed.handleApplyBlock('H5')"},
				{event:"Ctrl+6", handler:"xed.handleApplyBlock('H6')"},
				
				{event:"Ctrl+Alt+B", handler:"xed.handleApplyBlock('BLOCKQUOTE')"},
				{event:"Ctrl+Alt+D", handler:"xed.handleApplyBlock('DIV')"},
				{event:"Alt+HYPHEN", handler:"xed.handleSeparator()"},				
				
				{event:"Ctrl+Alt+O", handler:"xed.handleList('OL')"},
				{event:"Ctrl+Alt+U", handler:"xed.handleList('UL')"},
				
				{event:"Ctrl+Alt+E", handler:"xed.handleRemoveBlock()"},
				
				{event:"Alt+COMMA", handler:"xed.handleJustify('left')"},
				{event:"Alt+PERIOD", handler:"xed.handleJustify('center')"},
				{event:"Alt+SLASH", handler:"xed.handleJustify('right')"},
				
				{event:"Alt+UP", handler:"xed.handleMoveBlock(true)"},
				{event:"Alt+DOWN", handler:"xed.handleMoveBlock(false)"}
			];
		} else {
			// Win IE & FF
			return [
				{event:"Ctrl+SPACE", handler:"xed.handleAutocompletion(); stop = true;"},
				{event:"Alt+0", handler:"xed.handleApplyBlock('P')"},
				{event:"Alt+1", handler:"xed.handleApplyBlock('H1')"},
				{event:"Alt+2", handler:"xed.handleApplyBlock('H2')"},
				{event:"Alt+3", handler:"xed.handleApplyBlock('H3')"},
				{event:"Alt+4", handler:"xed.handleApplyBlock('H4')"},
				{event:"Alt+5", handler:"xed.handleApplyBlock('H5')"},
				{event:"Alt+6", handler:"xed.handleApplyBlock('H6')"},
				
				{event:"Ctrl+Alt+B", handler:"xed.handleApplyBlock('BLOCKQUOTE')"},
				{event:"Ctrl+Alt+D", handler:"xed.handleApplyBlock('DIV')"},
				{event:"Alt+HYPHEN", handler:"xed.handleSeparator()"},
				
				{event:"Ctrl+Alt+O", handler:"xed.handleList('OL')"},
				{event:"Ctrl+Alt+U", handler:"xed.handleList('UL')"},
				
				{event:"Ctrl+Alt+E", handler:"xed.handleRemoveBlock()"},
				
				{event:"Alt+COMMA", handler:"xed.handleJustify('left')"},
				{event:"Alt+PERIOD", handler:"xed.handleJustify('center')"},
				{event:"Alt+SLASH", handler:"xed.handleJustify('right')"},
				
				{event:"Alt+UP", handler:"xed.handleMoveBlock(true)"},
				{event:"Alt+DOWN", handler:"xed.handleMoveBlock(false)"}
			];
		}
	}
	
	var quickSearch = function(xed) {
		var dialog = new xq.controls.QuickSearchDialog(xed, {
			listProvider: function(query, xed, callback) {
				var headings = xed.rdom.searchHeadings();
				var matched = headings.findAll(function(h) {
					return xed.rdom.getInnerText(h).toLowerCase().indexOf(query.toLowerCase()) != -1;
				});
				callback(matched);
			},
			onSelect: function(xed, selected) {
				xed.focus();
				xed.rdom.scrollIntoView(selected, true, true);
			}
		});
		dialog.show();
	}
	
	var contextMenuHandler = function(editor, element, x, y) {
		if(element.nodeName != "P") return false;
		editor.showContextMenu([
			{
				title: '볼드',
				handler: editor.handleStrongEmphasis.bind(xed)
			},
			{
				title: '언더라인',
				handler: editor.handleUnderline.bind(xed)
			},
			{
				title: '----'
			},
			{
				title: 'QuickSearch',
				handler: quickSearch
			}
		], x, y);
		
		return true;
	}

	var xed = new xq.Editor('post_body');
	xed.config.imagePathForDefaultToolbar = XquaredPluginUri + '/img/toolbar/';
	xed.addShortcuts(getAdditionalShortcuts());
	xed.addAutocorrections(getAdditionalAutocorrections());
	xed.addAutocompletions(getAdditionalAutocompletions());
	xed.addContextMenuHandler('test', contextMenuHandler);
	xed.setEditMode('wysiwyg');
	xed.loadStylesheet(XquaredPluginUri + '/css/xq_contents_editor.css');

	setPostAttribute(document.getElementById('post_body').form, 'format', 'xquared-html');

	xed.focus();
}

if(Event && Event.observe)
	Event.observe(window, 'load', loadXq);
else if(window.addEvent)
	window.addEvent('domready', loadXq);
else {
	window.__old_onload = window.onload;
	window.onload = function() {
		var r = window.__old_onload && window.__old_onload();
		loadXq();
		return r;
	};
}
