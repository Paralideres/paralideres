/**
 * Created by Tarek on 5/23/2017.
 */

$(document).ready(function () {
    if($('#auth').length > 0) {
        new Vue({
            el: '#auth',

            data: {
                base_url: window.base_url,
                api_url: window.api_url,
                formData: null,
                submitDisable: false,
                errors: []
            },

            created: function(){

            },

            methods: {

                signup(form_id, signup){
                    this.formData = $('#' + form_id).serialize();
                    if (signup) {
                        this.$common.loadingShow(0);
                        this.submitDisable = true;
                        this.formData += '&signup=home_signup';
                    }

                    axios.post(this.base_url+this.api_url + 'users', this.formData).then(response => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = false;
                        if (response.data) {
                            this.$common.showMessage(response.data);
                            setTimeout(function () {
                                window.location.href = '/login';
                            }, 500)
                        }
                    }).catch(error => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = true;
                        if (error.response.status == 500 && error.response.data.code == 500) {
                            var error = error.response.data;
                            this.$common.showMessage(error);
                        } else if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                },


                login(form_id, login){
                    this.formData = $('#' + form_id).serialize();
                    // this.formData += '&grant_type='+this.grant_type+'&client_id='+this.client_id+'&client_secret='+this.client_secret+'&scope=';
                    if (login) {
                        this.$common.loadingShow(0);
                        this.submitDisable = true;
                        this.formData += '&login=login';
                    }
                    // axios.post('oauth/token',this.formData).then(response => {
                    // axios.post(this.api_url+'account/login',this.formData).then(response => {
                    axios.post(this.base_url + 'login', this.formData).then(response => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = false;
                        if (response.data) {
                            this.$common.showMessage(response.data);
                            localStorage.setItem('access_token', response.data.data.access_token);
                            // alert(window.redirect);
                            if(window.redirect == 'home'){
                                var url = this.base_url;
                            }else if(window.redirect == 'resource'){
                                var url = this.base_url+'resources/'+window.slug;
                            }else if(window.redirect == 'resource_list'){
                                var url = this.base_url+'resources';
                            }else{
                                url = this.base_url;
                            }
                            setTimeout(function () {
                                window.location.href = url;
                            }, 500)
                        }
                    }).catch(error => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = true;
                        if (error.response.status == 500 && error.response.data.code == 500) {
                            var error = error.response.data;
                            this.$common.showMessage(error);
                        } else if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                },


                logout(){
                    axios.post(window.base_url+'logout')
                        .then(response => {
                            this.$common.showMessage(response.data);
                            localStorage.removeItem('access_token');
                            window.axios.defaults.headers.common['Authorization'] = '';
                            setTimeout(function () {
                                window.location.href = this.base_url;
                            }, 500)
                        });
                },


                passwordResetEmail(form_id, reset){
                    this.formData = $('#' + form_id).serialize();
                    if (reset) {
                        this.$common.loadingShow(0);
                        this.submitDisable = true;
                        this.formData += '&reset=reset';
                    }
                    // document.getElementById("password_reset_form").reset();
                    // $('#'+form_id)[0].reset();

                    axios.post('/password/email', this.formData).then(response => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = false;
                        if (response.data) {
                            this.$common.showMessage(response.data);
                            this.submitDisable = true;
                            setTimeout(function () {
                                window.location.href = '/login';
                            }, 1000)
                        }
                    }).catch(error => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = true;
                        if (error.response.status == 500 && error.response.data.code == 500) {
                            var error = error.response.data;
                            this.$common.showMessage(error);
                        } else if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                },

                passwordReset(form_id, reset){
                    this.formData = $('#' + form_id).serialize();
                    if (reset) {
                        this.$common.loadingShow(0);
                        this.submitDisable = true;
                        this.formData += '&reset=reset';
                    }

                    axios.post('/password/reset', this.formData).then(response => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = false;
                        if (response.data) {
                            this.$common.showMessage(response.data);
                            this.submitDisable = true;
                            setTimeout(function () {
                                window.location.href = '/dashboard';
                            }, 1000)
                        }
                    }).catch(error => {
                        this.$common.loadingHide(0);
                        this.errors = [];
                        this.submitDisable = true;
                        if (error.response.status == 500 && error.response.data.code == 500) {
                            var error = error.response.data;
                            this.$common.showMessage(error);
                        } else if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                }


            }

        });

    }
});

