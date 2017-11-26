/**
 * Created by Tarek on 10/20/2017.
 */
$(document).ready(function(){
new Vue({
    el: '#app',
    data:{
        asset: window.asset,
        base_url: window.base_url,
        api_url: window.api_url,
        resources: [],
    },

    created(){
        this.getResources();
    },

    filters: {
        truncate: function (string, value) {
            return string.substring(0, value) + '...';
        }
    },

    methods: {

        getResources(){
            this.$common.loadingShow(0);
            axios.get(this.api_url+'resources?'+search_text+tag_slug+cat_slug)
                .then(response => {
                    this.resources = response.data.data;
                    this.$common.loadingHide(0);
                    // console.log(response);
                    // console.log(this.resources);
                });
        },

        getNextResources(next_page_url){
            this.$common.loadingShow(0);
            axios.get(next_page_url)
            .then(response => {
                this.resources = response.data.data;
                this.$common.loadingHide(0);
                // console.log(response);
                // console.log(this.resources);
            });
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        },

        filterResource(){
            var formData = $('#filterResource').serialize();
            console.log(formData);
            axios.get(this.api_url+'resources?'+formData)
            .then(response => {
                this.resources = response.data.data;
                // console.log(response);
                // console.log(this.resources);
            });
        },

        givenResourceLike(resource){
            axios.post(this.api_url+'resources/'+resource.id+'/like')
                .then(response => {
                    if(response.data.status == 'like'){
                        if(resource.likes_count.length > 0){
                            resource.likes_count[0].total +=1;
                        }else{
                            resource.likes_count = [{'resource_id': resource.id,'total':1}];
                        }
                        resource.like = [{'resource_id': resource.id,'user_id':null}];
                    }else if(response.data.status == 'unlike'){
                        if(resource.likes_count.length > 0){
                            resource.likes_count[0].total -=1;
                        }else{
                            resource.likes_count = [{'resource_id': resource.id,'total':0}];
                        }
                        resource.like = [];
                    }
                });
        },


    }

});
});