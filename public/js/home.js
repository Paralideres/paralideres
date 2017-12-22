$(document).ready(function () {
    new Vue({
        el: '#app',
        data: {
            asset: window.asset,
            base_url: window.base_url,
            img_path: window.img_path,
            api_url: window.api_url,
            resources: [],
            checkedAnswer: 0,
            poll: [],
            pollResult: false
        },
        created: function () {
            this.getResources();
            this.getLastPoll();
        },
        filters: {
            truncate: function (string, value) {
                return string.substring(0, value) + '...';
            }
        },
        methods: {
            getResources: function (search, value) {
                var THIS = this;
                console.log('amieami');
                THIS.$common.loadingShow(0);
                if (search != null) {
                    var action_url = '/' + search + '?search=' + value;
                } else {
                    var action_url = '';
                }

                axios.get(this.api_url + 'resources' + action_url)
                    .then(function (response) {
                        THIS.resources = response.data.data;
                        THIS.$common.loadingHide(0);
                    });
            },
            getNextResources: function (next_page_url) {
                var THIS = this;
                THIS.$common.loadingShow(0);
                axios.get(next_page_url).then(function (response) {
                    THIS.resources = response.data.data;
                    THIS.$common.loadingHide(0);
                });

                $('html, body').animate({scrollTop: 0}, 500);
            },
            getCategoryResources: function (slug) {
                var THIS = this;
                this.$common.loadingShow(0);
                axios.get(THIS.api_url + 'categories/' + slug + '/resources').then(function (response) {
                    THIS.resources = response.data.data;
                    THIS.$common.loadingHide(0);
                });

            },
            givenResourceLike: function (resource) {
                var THIS = this;
                axios.post(THIS.api_url + 'resources/' + resource.id + '/like').then(function (response) {
                    if (response.data.status == 'like') {

                        if (resource.likes_count.length > 0) {

                            resource.likes_count[0].total = parseInt(resource.likes_count[0].total) + 1;

                        } else {

                            resource.likes_count = [{'resource_id': resource.id, 'total': 1}];

                        }

                        resource.like = [{'resource_id': resource.id, 'user_id': null}];

                    }
                    else if (response.data.status == 'unlike') {

                        if (resource.likes_count.length > 0) {

                            resource.likes_count[0].total = parseInt(resource.likes_count[0].total) - 1;

                        } else {

                            resource.likes_count = [{'resource_id': resource.id, 'total': 0}];

                        }

                        resource.like = [];

                    }
                });

            },
            getLastPoll: function () {
                var THIS = this;
                axios.get(THIS.api_url + 'polls/last')
                    .then(function (response) {
                        THIS.poll = response.data.data;
                    });
            },
            votePoll: function () {
                var THIS = this;
                var formID = $('#votePoll');
                var option = formID.find('input:radio[name="poll_option"]:checked').val();
                var formData = {
                    option: option
                };
                THIS.$common.loadingShow(0);
                axios.post(THIS.base_url + THIS.api_url + 'polls/' + THIS.poll.id + '/vote', formData)
                    .then(function (response) {
                        THIS.$common.loadingHide(0);
                        if (response.data.data.status === 2000) {
                            THIS.pollResult = true;
                            THIS.poll = response.data.data.poll;
                            THIS.$common.showMessage(response.data);
                            THIS.checkedAnswer = option;
                        } else if (response.data.data.status === 3000) {
                            THIS.pollResult = true;
                            THIS.poll = response.data.data.poll;
                            THIS.$common.showMessage(response.data);
                            THIS.checkedAnswer = response.data.data.has;
                        }
                    })
                    .catch(function (error) {
                        THIS.$common.loadingHide(0);
                        if (error.response.status === 500 && error.response.data.code === 500) {
                            THIS.$common.showMessage(error);
                        }
                    });

            },
        }
    });
});


/*
$(document).ready(function(){
    new Vue({
        el: '#app',
        data:{
            asset: window.asset,
            base_url: window.base_url,
            api_url: window.api_url,
            resources: [],
            poll:[],
            pollResult:false
        },

        created(){
            this.getResources();
            this.getLastPoll();
        },

        filters: {
            truncate: function (string, value) {
                return string.substring(0, value) + '...';
            }
        },

        methods: {

            getResources(search=null, value=null){
                this.$common.loadingShow(0);
                if(search !=null){
                    var action_url = '/'+search+'?search='+value;
                }else{
                    var action_url = '';
                }
                axios.get(this.api_url+'resources'+action_url)
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

            getCategoryResources(slug){
                this.$common.loadingShow(0);
                axios.get(this.api_url+'categories/'+slug+'/resources')
                    .then(response => {
                    this.resources = response.data.data;
                this.$common.loadingHide(0);
                // console.log(response);
                // console.log(this.resources);
            });
            },

            resourceDownload(resource){

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

            getLastPoll(){
                axios.get(this.api_url+'polls/last')
                    .then(response => {
                    this.poll = response.data.data;
                // console.log(response);
                // console.log(this.poll);
            });
            },

            votePoll(){
                let formID = document.querySelector('#votePoll');
                let formData = new FormData(formID);
                this.$common.loadingShow(0);
                axios.post(this.base_url+this.api_url+'polls/'+this.poll.id+'/vote', {
                    'option': formData.get('poll_option')
                })
                    .then(response => {
                    this.$common.loadingHide(0);
                this.pollResult = true;
                this.poll = response.data.data;
                this.$common.showMessage(response.data);
                console.log(this.pollResult, response.data.data);
            })
            .catch(error => {
                    this.$common.loadingHide(0);
                if (error.response.status == 500 && error.response.data.code == 500) {
                    this.$common.showMessage(error);
                }
            });
            },

        }

    });
});*/
