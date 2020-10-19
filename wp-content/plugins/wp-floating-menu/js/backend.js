(function ($) {
    $(function () {
        $('.wpfm-tabs-trigger').click(function () {
            $('.wpfm-tabs-trigger').removeClass('nav-tab-active');
            $('.wpfm-tab-contents').hide();
            $(this).addClass('nav-tab-active');
            var board_id = 'tab-' + $(this).attr('id');
            $('body').find('#' + board_id).show();
        });

        /** Menu lists pages,post,category options Slide Toggle */
        $('.wpfm-menu-list-field').children('.wpfm-field-header').click(function () {
            $(".wpfm-list-inner-content").not($(this).toggleClass('wpfm-open-menu-list').next('.wpfm-list-inner-content').slideToggle(300)).hide(300);
            $(this).find('i').toggleClass('fa-sort-down fa-short-up');
        });

        /** Menu option checkbox click for page function */
        $('.wpfm-menu-list-field').find(".wpfm-submit-add-to-menu").click(function (e) {
            e.preventDefault();
            var submit_id = $(this).attr('id');
            var submit_field_data = $(this).attr('submit-field-val');

            if (submit_id == 'wpfm-submit-posttype-custom-link') {
                var custom_link_name = $('input[name="wpfm_custom_link_text"]').val();
                var custom_link_url = $('input[name="wpfm_custom_link_url"]').val();
                var custom_link_field_data = $(this).attr('field-data');
                var form_key_count = $('.wpfm-menu-count').val();
                form_key_count++;
                $('.wpfm-sortable-menu-field').find('.wpfm-menu-count').val(form_key_count);
                if (custom_link_url == '' || custom_link_name == '') {
                    $('input[name="wpfm_custom_link_url"], input[name="wpfm_custom_link_text"]').css("border-color", "red");
                } else {
                    $('input[name="wpfm_custom_link_url"], input[name="wpfm_custom_link_text"]').css("border-color", "#ddd");
                    if (custom_link_name != '' && custom_link_url != '') {
                        $.ajax({
                            url: wpfm_backend_js_params.ajax_url,
                            data: {
                                submit_field_data: submit_field_data,
                                custom_link_name: custom_link_name,
                                custom_link_url: custom_link_url,
                                custom_link_field_data: custom_link_field_data,
                                _wpnonce: wpfm_backend_js_params.ajax_nonce,
                                action: 'wpfm_pull_data_contents'
                            },
                            type: 'post',
                            beforeSend: function () {
                                $('.wpfm-view-wrap').show();
                            },
                            success: function (response) {
                                $('.wpfm-menu-temp-holder').html(response);
                                $('.wpfm-menu-temp-holder input').each(function () {
                                    var name_attr = $(this).attr('name');
                                    if (name_attr) {
                                        name_attr = name_attr.replace('menu_id', form_key_count);
                                        $(this).attr('name', name_attr);
                                    }
                                });
                                $('.wpfm-menu-temp-holder select').each(function () {
                                    var name_attr2 = $(this).attr('name');
                                    if (name_attr2) {
                                        name_attr2 = name_attr2.replace('menu_id', form_key_count);
                                        $(this).attr('name', name_attr2);
                                    }
                                });
                                $('.wpfm-menu-temp-holder .wpfm-icon-picker').each(function () {
                                    var name_attr3 = $(this).attr('id');
                                    if (name_attr3) {
                                        $(this).attr('id', 'wpfm-icon-picker-icon_' + form_key_count);
                                    }
                                });
                                $('.wpfm-menu-temp-holder #wpfm-menu-icon-div').each(function () {
                                    var name_attr4 = $(this).attr('data-target');
                                    if (name_attr4) {
                                        $(this).attr('data-target', '#wpfm-icon-picker-icon_' + form_key_count);
                                    }
                                });
                                var html_fields = $('.wpfm-menu-temp-holder').html();
                                $('.wpfm-sortable-menu-field').append(html_fields).show('slow');
                                $('.wpfm-sortable-menu-field .wpfm-colorpicker-trigger').wpColorPicker();
                            },
                            complete: function () {
                                $(".wpfm-add-page-fields")[0].reset();
                                $('.wpfm-view-wrap').hide();
                                $('.wpfm-menu-temp-holder').html('');
                            }
                        });
                    }
                }
            }
        });

        /** Slide toggle for menu inner fields */
        $('.wpfm-sortable-menu-field').on('click', 'span.wpfm-ind-menu-toggle-icon', function () {
            $(this).parents().children('div[id^="wpfm-menu-item-settings"]').slideToggle(300);
            $(this).find('i').toggleClass('fa-sort-down fa-sort-up');
            $(this).parents().children('li.wpfm-menu-item').toggleClass('wpfm-open-menu-div');
            $('.icon-picker').iconPicker();
        });

        /** Delete Menu when clicked remove */
        $('.wpfm-sortable-menu-field').on("click", ".wpfm-remove-menu", function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete? All of it\'s associated settings will also be deleted.')) {
                $(this).parents('ul.wpfm-menu').fadeOut(200, function () {
                    $(this).remove();
                });
            }
            return false;
        });

        /** Menu icon color selector show hide */
        $('body').on('change', '.wpfm-icon-color-type', function () {
            if ($(this).val() == 'custom') {
                $(this).parents('.wpfm-menu-item').find('.wpfm-menu-icon-color-setting').slideDown(200);
            } else {
                $(this).parents('.wpfm-menu-item').find('.wpfm-menu-icon-color-setting').slideUp(200);
            }
        });

        /** Sortable initialization */
        $('.wpfm-sortable-menu-field').sortable({
            containment: "parent",
            cursor: 'move',
            revert: true,
            axis: 'y',
            opacity: 0.9
        });

        /** show hide template number according to custom template selected */
        $('body').on('change', '#wpfm-menu-type', function () {
            if ($(this).val() == 'buildin') {
                $('#buildin-temple-listing').slideDown(200);
                $('#wpfm-custom-template-type').slideUp(100);
            } else {
                $('#buildin-temple-listing').slideUp(200);
                $('#wpfm-custom-template-type').slideDown(100);
            }
        });

        /** 
         * Live Preview Section 
         */
        /** Menu Template demo display */

        // Live Template preview For both menu and custom template
        $(".wpfm-common").first().addClass("temp-active");
        $('#menu-template').on('change', function () {
            template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            if (current_id == '1' || current_id == '2' || current_id == '3' || current_id == '4' || current_id == '5') {
                $('#wpfm-temp-demo-' + current_id).removeClass('temp-active');
                $('.wpfm-common').hide();
                $(this).addClass('temp-active');
                $('#wpfm-temp-demo-' + current_id).show();
            } else {
                $('#wpfm-temp-demo-' + current_id).removeClass('temp-active');
                $('.wpfm-common').hide();
                $(this).addClass('temp-active');
                $('#wpfm-temp-demo-' + current_id).show();
            }
        });

        if ($("#menu-template option:selected")[0]) {
            cur_temp_val = $('#menu-template option:selected').val();
            var array_break = cur_temp_val.split('-');
            var current_id = array_break[1];
            $('.wpfm-common').hide();
            $('#wpfm-temp-demo-' + current_id).show();
        }

        //change description dynamically
        $('.wpfm-sortable-menu-field').on('keyup', '#wpfm-edit-menu-item-item-title', function () {
            var desc = $(this).val();
            $(this).parents('ul.wpfm-menu').find('span.wpfm-menu-item-title').text(desc);
        });

        // Font Family Live Preview For Menu Title 
        $('.wpfm-menu-title-font').change(function () {
            var t_label_font = $(this).val();
            var t_font_size = $("#wpfm-title-font-size option:selected").text();
            var t_font_color = $("#wpfm-title-font-color").val();
            var t_text_transform = $('#wpfm-title-text-transform option:selected').val();
            $(".title-font-style").html('');
            $("#wpfm-font-family").css({
                'font-family': t_label_font,
                'font-size': t_font_size + 'px',
                'text-transform': t_text_transform,
                'color': t_font_color
            });
            if (t_label_font != "default" && t_label_font != '') {
                WebFont.load({
                    google: {
                        families: [t_label_font]
                    }
                });
            }
        });

        /** show hide custom icon field according to select option selected */
        $('body').on('change', '.wpfm-icon-type', function () {
            if ($(this).val() == 'default') {
                $(this).parents('.wpfm-menu-item').find('#wpfm-field-add-custom-menu-icons').slideUp(200);
                $(this).parents('.wpfm-menu-item').find('#wpfm-field-add-menu-icons').slideDown(100);
            } else {
                $(this).parents('.wpfm-menu-item').find('#wpfm-field-add-custom-menu-icons').slideDown(200);
                $(this).parents('.wpfm-menu-item').find('#wpfm-field-add-menu-icons').slideUp(100);
            }
        });
        // Font Size Live Preview For Menu Title 
        $("#wpfm-title-font-size").change(function () {
            var size = $('#wpfm-title-font-size option:selected').val();
            var family = $(".wpfm-menu-title-font option:selected").text();
            var font_color = $("#wpfm-title-font-color").val();
            var text_transform = $('#wpfm-title-text-transform option:selected').val();
            $("#wpfm-font-family").css({
                'font-family': family,
                'font-size': size + 'px',
                'text-transform': text_transform,
                'color': font_color
            });
        });

        // Text Transform Preview For Menu Title
        $("#wpfm-title-text-transform").change(function () {
            var text_tran_font_family = $(".wpfm-menu-title-font option:selected").text();
            var text_tran_font_font_color = $("#wpfm-title-font-color").val();
            var text_tran_font_size = $("#wpfm-title-font-size option:selected").val();
            var text_tran_text_transform = $(this).val();
            $("#wpfm-font-family").css({
                'font-family': text_tran_font_family,
                'font-size': text_tran_font_size + 'px',
                'text-transform': text_tran_text_transform,
                'color': text_tran_font_font_color
            });
        });

        // Color picker live previewfor Title font color
        var myOptions = {
            palettes: true,
            change: function (event, ui) {
                $('#wpfm-font-family').css('color', ui.color.toString());
            },

        };

        // Implementing Font Color for Title
        $('#wpfm-title-font-color').wpColorPicker(myOptions);

        // Font Family For tooltip Text 
        $('#wpfm-menu-tooltip-font').change(function () {
            var tt_label_font = $(this).val();
            var tt_label_font_color = $('#wpfm-title-font-color option:selected').val();
            var tt_font_size = $("#wpfm-tooltip-font-size option:selected").text();
            var tt_text_transform = $('#wpfm-tt-title-text-transform').val();
            $("#wpfm-tooltip-font-family").css({
                'font-family': tt_label_font,
                'font-size': tt_font_size + 'px',
                'color': tt_label_font_color,
                'text-transform': tt_text_transform
            });
            if (tt_label_font != "default" && tt_label_font != '') {
                WebFont.load({
                    google: {
                        families: [tt_label_font]
                    }
                });
            }
        });

        // Font Size Live Preview For Tooltip Text 
        $("#wpfm-tooltip-font-size").change(function () {
            var family = $("#wpfm-menu-tooltip-font option:selected").text();
            var size = $(this).val();
            var font_color = $('#wpfm-title-font-color option:selected').val();
            var text_transform = $('#wpfm-tt-title-text-transform').val();
            $("#wpfm-tooltip-font-family").css({
                'font-family': family,
                'font-size': size + 'px',
                'color': font_color,
                'text-transform': text_transform
            });
        });

        // Text Transform Preview For Tooltip Title
        $("#wpfm-tt-title-text-transform").change(function () {
            var text_tran_font_family = $('#wpfm-menu-tooltip-font option:selected').val();
            var text_tran_font_font_color = $('#wpfm-title-font-color option:selected').val();
            var text_tran_font_size = $("#wpfm-tooltip-font-size option:selected").text();
            var text_tran_text_transform = $(this).val();
            $("#wpfm-tooltip-font-family").css({
                'font-family': text_tran_font_family,
                'font-size': text_tran_font_size + 'px',
                'text-transform': text_tran_text_transform,
                'color': text_tran_font_font_color
            });
        });

        // Color picker live previewfor Menu Tooltip font color
        var myOptions = {
            palettes: true,
            change: function (event, ui) {
                $('#wpfm-tooltip-font-family').css('color', ui.color.toString());
            },

        };

        // Implementing Font Color for tooltip
        $('#wpfm-tooltip-font-color').wpColorPicker(myOptions);

        // color picker live previewfor Menu Tooltip font color
        var myOptions = {
            palettes: true,
            change: function (event, ui) {
                $('#tt-demo-wrap-wpfm').css('background', ui.color.toString());
            },

        };

        // Implementing Font Color for tooltip
        $('#wpfm-tooltip-bg-color').wpColorPicker(myOptions);

        /**  
         * Display select hidden field options for Custom Template
         */
        /** Expand/Hide icon selector Option for Custom Template */
        $('body').on('change', '#menu-template', function () {
            var template_val = $(this).val();
            if (template_val == 'template-5') {
                $('.layout-5689-hidden-field').slideDown(200);
                $('#wpfm-nav12347-icon-color-wrap').hide(200);
                $('.layout-4610-hidden-field').hide();
            } else if (template_val == 'template-1' || template_val == 'template-2' || template_val == 'template-3') {
                $('.layout-5689-hidden-field').slideUp(200);
                $('#wpfm-nav12347-icon-color-wrap').show(200);
                $('.layout-4610-hidden-field').hide();
            } else if (template_val == 'template-4') {
                $('.layout-5689-hidden-field').slideUp(200);
                $('#wpfm-nav12347-icon-color-wrap').show(200);
                $('.layout-4610-hidden-field').show();
            } else {
                $('#wpfm-nav12347-icon-color-wrap').show(200);
                $('.layout-5689-hidden-field').slideUp(200);
                $('.layout-4610-hidden-field').hide();
            }
        });

        /**
         * End for Display select hidden field options for Custom Template
         */
        /* Check for Empty name field in template add*/
        $('.wpfm-template-save-button').click(function (e) {
            var template_name = $('.wpfm-custom-template-name').val();
            if (template_name == '') {
                $('.template-name-error').show();
                e.preventDefault();
            }
        });

        /* Check for Empty name field in Menu add */
        $('.wpfm-menu-field-add').click(function (e) {
            var template_name = $('#wpfm-menu-name-add').val();
            if (template_name == '') {
                $('.menu-name-error').show();
                e.preventDefault();
            }
        });

        /** Implement color picker for menu background color and menu title font color */
        $('.wpfm-colorpicker-trigger').wpColorPicker();
        if ($('.icon-picker').length > 0) {
            $('body').find('.icon-picker').iconPicker();
        }
    }); /** Function ends */
}(jQuery));