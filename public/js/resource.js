/**
 * Created by Tarek on 10/20/2017.
 */

new Vue({
    el: '#app',
    data:{
        asset: window.asset,
        base_url: window.base_url,
        api_url: window.api_url,
        userLike: userLike,
    },


    methods: {

        givenResourceLike(resource_id){
            axios.post(this.base_url+this.api_url+'resources/'+resource_id+'/like')
                .then(response => {
                    if(response.data.status == 'like'){
                        this.userLike = true;
                    }else if(response.data.status == 'unlike'){
                        this.userLike = false;
                    }
                });
        },


    }

});