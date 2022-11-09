var globConfig = {
    selectionStyle: "text",
    readOnly: true,
    showLineNumbers: false,
    showGutter: false
};

var configJs = {
    mode: "ace/mode/javascript",
    theme: "ace/theme/monokai",
    ...globConfig
};

var configJson = {
    mode: "ace/mode/javascript",
    theme: "ace/theme/github",
    ...globConfig
};

var configHtml = {
    mode: "ace/mode/html",
    theme: "ace/theme/monokai",
    ...globConfig
};

var editor_get = ace.edit('get_exemple', configJs),
    editor_add = ace.edit('add_exemple', configJs),
    editor_edit = ace.edit('edit_exemple', configJs),
    editor_delete = ace.edit('delete_exemple', configJs),
    editor_form = ace.edit('add_form', configHtml),
    editor_form_ts = ace.edit('add_form_ts', configJs),
    editor_edit_form_ts = ace.edit('edit_form_ts', configJs),
    editor_form_html = ace.edit('edit_form', configHtml);

var json_add = ace.edit('json_add', configJson),
    json_edit = ace.edit('json_edit', configJson),
    json_delete = ace.edit('json_delete', configJson);