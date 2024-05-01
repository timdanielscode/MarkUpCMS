var button = new Button(new Section());

button.setOnclickEventPostEdit(button.getSlugElement());
button.setOnclickEventPostEdit(button.getCategoryElement());
button.setOnclickEventPostEdit(button.getMetaElement());
button.setOnclickEventPostEdit(button.getCdnElement());
button.setOnclickEventPostEdit(button.getJsElement());
button.setOnclickEventPostEdit(button.getCssElement());
button.setOnclickEventPostEdit(button.getWidgetElement());

var editor = new Editor();

button.setOnclickEventFullscreen(editor.getElement(), editor.getBodyElement(), button.getFullscreenElement())

button.setOnclickEventZoomIn(button.getZoomInElement(), editor);
button.setOnclickEventZoomOut(button.getZoomOutElement(), editor);