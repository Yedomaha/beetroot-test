(function () {
    tinymce.PluginManager.add('true_mce_button', function (editor, url) {
        editor.addButton('true_mce_button', {
            icon: 'shortcodeIcon',
            type: 'menubutton',
            title: 'Add shortcodes',
            menu: [
                //Shortcode button
                {
                    text: 'Buttons',
                    onclick: function () {
                        editor.windowManager.open({
                            title: 'Set the field parameters',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'titleButton',
                                    label: 'Button title',
                                    value: ''
                                },
                                {
                                    type: 'textbox',
                                    name: 'linkButton',
                                    label: 'Button link',
                                    value: '',
                                    // multiline: true,
                                    // minWidth: 300,
                                    // minHeight: 100
                                },
                                {
                                    type: 'listbox',
                                    name: 'stylesButton',
                                    label: 'Button styles',
                                    'values': [
                                        {text: 'Style 1', value: '1'},
                                    ]
                                }
                            ],
                            onsubmit: function (e) { //this will happen after filling in the fields and clicking the submit button
                                editor.insertContent('[buttons title="' + e.data.titleButton + '" link="' + e.data.linkButton + '" style="' + e.data.stylesButton + '"]');
                            }
                        });
                    }
                },
                //End Shortcode button

                //Read more
                {
                    text: 'Read more',
                    onclick: function () {
                        editor.insertContent('[read_more]');
                    }
                },
                //End Read more

                // Line break
                {
                    text: 'Line break',
                    onclick: function () {
                        editor.insertContent('[br]');
                    }
                },
                //End Line break
            ]
        });
    });
})();