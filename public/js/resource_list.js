/**
 * Created by Tarek on 10/20/2017.
 */
// $(document).ready(function () {
new Vue({
    el: '#app',
    data: {
        asset: window.asset,
        base_url: window.base_url,
        img_path: window.img_path,
        api_url: window.api_url,
        resources: [],
    },
    created: function () {
        this.getResources();
    },
    filters: {
        truncate: function (string, value) {
            return string.substring(0, value) + '...';
        }
    },
    methods: {
        getResources: function () {
            var THIS = this;
            THIS.$common.loadingShow(0);
            axios.get(THIS.api_url + 'resources?' + search_text + tag_slug + cat_slug)
                .then(function (response) {
                    THIS.resources = response.data.data;
                    THIS.$common.loadingHide(0);
                    setTimeout(function () {
                        addthis.toolbox('.addthis_toolbox')
                    }, 2000);
                });
        },
        getNextResources: function (next_page_url) {
            var THIS = this;
            THIS.$common.loadingShow(0);
            axios.get(next_page_url)
                .then(function () {
                    THIS.resources = response.data.data;
                    THIS.$common.loadingHide(0);
                });
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        },

        filterResource: function () {
            var THIS = this;
            var formData = $('#filterResource').serialize();
            console.log(formData);
            axios.get(THIS.api_url + 'resources?' + formData)
                .then(function () {
                    THIS.resources = response.data.data;
                });
        },

        givenResourceLike: function (resource) {
            var THIS = this;
            axios.post(THIS.api_url + 'resources/' + resource.id + '/like')
                .then(function () {
                    if (response.data.status == 'like') {
                        if (resource.likes_count.length > 0) {
                            resource.likes_count[0].total += 1;
                        } else {
                            resource.likes_count = [{'resource_id': resource.id, 'total': 1}];
                        }
                        resource.like = [{'resource_id': resource.id, 'user_id': null}];
                    } else if (response.data.status == 'unlike') {
                        if (resource.likes_count.length > 0) {
                            resource.likes_count[0].total -= 1;
                        } else {
                            resource.likes_count = [{'resource_id': resource.id, 'total': 0}];
                        }
                        resource.like = [];
                    }
                });
        },


    }

});
// });