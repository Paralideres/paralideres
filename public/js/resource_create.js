/**
 * Created by Tarek on 10/21/2017.
 */
$(document).ready(function () {
    if ($('#create_resource_popup').length > 0) {
        new Vue({
            el: '#create_resource_popup',
            data: {
                errors1: [],
                errors2: [],
                errors3: [],
                recurso: {
                    title: '',
                    description: '',
                    cat_id: '',
                    catThumb: '',
                }
            },
            created: function () {
                $("#old_tag").show();
                $("#new_tag").hide();
            },
            methods: {
                categorySelect: function () {
                    this.recurso.catThumb = window.img_path + '/images/icon/cat-icon-' + this.recurso.cat_id + '.png'
                },
                newTag: function () {
                    $('#select2').prop('selectedIndex', -1);
                    $("#old_tag").hide();
                    $("#new_tag").show();
                },

                oldTag: function () {
                    $('#newTag').val("");
                    $("#old_tag").show();
                    $("#new_tag").hide();
                },

                option1: function () {
                    $(".step_2").hide();
                    $(".step_3").show();
                },

                option2: function () {
                    $(".step_2").hide();
                    $(".step_4").show();
                },

                closePopup: function () {
                    $('body').removeClass('bodyScroll');
                    $(".popup_content").removeClass("open_content");
                    $(".step_1 ,.step_2 ,.step_3, .step_4").hide();
                },

                back1: function () {
                    $(".step_1").show();
                    $(".step_2").hide();
                },

                back2: function () {
                    $(".step_2").show();
                    $(".step_3").hide();
                    $(".step_4").hide();
                },
                createResourceSetp1: function () {
                    var THIS = this;
                    var title = $('#create_resource_form').find('input[name="title"]').val();
                    var review = $('#create_resource_form').find('textarea[name="review"]').val();
                    var category_id = $('#create_resource_form').find('select[name="category_id"]').val();
                    var tag_ids = $('#create_resource_form').find('select[name="tag_ids[]"]').val();

                    if (title.length > 0 && review.length > 0 && category_id > 0 && tag_ids.length > 0) {
                        THIS.$common.loadingShow(0);
                        var formData = $('#create_resource_form').serialize();
                        formData += '&step=1';

                        axios.post(window.base_url + window.api_url + 'resources', formData)
                            .then(function (response) {
                                THIS.$common.loadingHide(0);
                                THIS.errors1 = [];
                                // this.$common.showMessage(response.data);
                                // this.resources = response.data.data;
                                $(".step_1").hide();
                                $(".step_2").show();
                            })
                            .catch(function (error) {
                                console.log(error);
                                console.log(error.response);
                                THIS.$common.loadingHide(0);
                                THIS.errors1 = [];
                                if (error.response.status == 500 && error.response.data.code == 500) {
                                    var error = error.response.data;
                                    THIS.$common.showMessage(error);
                                } else if (error.response.status == 422) {
                                    THIS.errors1 = error.response.data.errors;
                                }
                            });
                    }
                },


                createResourceSetp2: function () {
                    var THIS = this;
                    THIS.$common.loadingShow(0);
                    // var formData = new FormData(e.target);

                    var formData = $('#create_resource_form').serialize();
                    formData += '&step=2';

                    axios.post(window.base_url + window.api_url + 'resources', formData)
                        .then(function (response) {
                            THIS.$common.loadingHide(0);
                            THIS.errors2 = [];
                            THIS.$common.showMessage(response.data);
                            // this.resources = response.data.data;
                            THIS.closePopup();
                        }).catch(function (error) {
                        THIS.$common.loadingHide(0);
                        THIS.errors2 = [];
                        if (error.response.status == 500 && error.response.data.code == 500) {
                            var error = error.response.data;
                            // this.$common.showMessage(error);
                        } else if (error.response.status == 422) {
                            THIS.errors2 = error.response.data.errors;
                        }
                    });
                },


                createResourceSetp3: function (e) {
                    var THIS = this;
                    THIS.$common.loadingShow(0);

                    var form = document.querySelector('#create_resource_form');
                    var formData = new FormData(form);
                    formData.append('step', 3);

                    // var formData = $('#create_resource_form').serialize();
                    // formData += '&step=3';

                    axios.post(window.base_url + window.api_url + 'resources', formData)
                        .then(function (response) {
                            THIS.$common.loadingHide(0);
                            THIS.errors3 = [];
                            THIS.$common.showMessage(response.data);
                            // this.resources = response.data.data;
                            THIS.closePopup();
                        }).catch(function (error) {
                        THIS.$common.loadingHide(0);
                        THIS.errors3 = [];
                        if (error.response.status == 500 && error.response.data.code == 500) {
                            var error = error.response.data;
                            // this.$common.showMessage(error);
                        } else if (error.response.status == 422) {
                            THIS.errors3 = error.response.data.errors;
                        }
                    });
                },


                // createResource(e){
                //     this.$common.loadingShow(0);
                //     var formData = new FormData(e.target);
                //     axios.post(window.base_url + window.api_url + 'resources', formData)
                //         .then((response) => {
                //         this.$common.loadingHide(0);
                //         this.errors = [];
                //         this.$common.showMessage(response.data);
                //         this.resources = response.data.data;
                //         e.target.reset();
                //     }).catch((error) => {
                //             this.$common.loadingHide(0);
                //         this.errors = [];
                //         if (error.response.status == 500 && error.response.data.code == 500) {
                //             var error = error.response.data;
                //             this.$common.showMessage(error);
                //         } else if (error.response.status == 422) {
                //             this.errors = error.response.data.errors;
                //         }
                //     });
                // }
            }
        });
    }

    $('body').on('click', '.select2-results__message', function () {
        var text = $('.select2-search__field').val();
        $('.select2-search__field').val('');
        $('.select2-search__field').focus();
        $(this).closest('.select2-container').remove();
        console.log(text);
        $.ajax({
            url: window.asset + "api/v1/tags/create",
            data: {tag: text},
            method: 'POST',
            success: function (res) {
                res = JSON.parse(res);
                if (res.status === 2000) {
                    var target = $('#select2');
                    var html = '<option value="' + res.tag.id + '" selected>' + res.tag.label + '</option>';
                    target.append(html);
                    target.select2();
                }
            }
        });
    });

    $('body').on('keydown', '.select2-search__field', function (e) {
        if (e.which === 188 || e.which === 13) {
            e.preventDefault()
        }
    });
    $('body').on('keyup', '.select2-search__field', function (e) {
        var trigger = $(this);
        if (e.which === 188 || e.which === 13) {
            e.preventDefault();
            var text = trigger.val();
            trigger.val('');
            trigger.focus();
            console.log(text);
            text = text.replace(",", "");
            $.ajax({
                url: window.asset + "api/v1/tags/create",
                data: {tag: text},
                method: 'POST',
                success: function (res) {
                    res = JSON.parse(res);
                    if (res.status === 2000) {
                        var target = $('#select2');
                        var html = '<option value="' + res.tag.id + '" selected>' + res.tag.label + '</option>';
                        target.append(html);
                        target.select2();
                        trigger.focus();
                    }
                }
            });
        }
    });
});