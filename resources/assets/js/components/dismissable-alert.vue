<template>
    <transition v-on:enter="afterEnter">
        <div v-if="isSeen" v-bind:id="alert.alertId" v-bind:class="usedCssClasses" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
            <strong><span v-html="alert.title"></span></strong> 
            <span v-html="alert.content"></span>
        </div>
    </transition>
</template>

<script>

import * as jQuery from 'jquery'; // 'jquery/dist/jquery'

export default {
    name: 'dismissable-alert',
    props: ['alert'], //['cssClasses', 'title', 'content', 'alertId', 'timeout' ],
    /*
    data: function () {
        return {
            cssClasses: this.cssClasses,
            title: '',
            content: '',
            alertId: 'masterPageAlert'
        }
    }, 
    */
    computed: {
        usedCssClasses: function() {
            let cSS = this.alert.cssClasses.trim();
            return "alert " + (cSS !== ''? cSS : 'alert-info') + " alert-dismissible fade in";
        },
        isSeen: function() {
            return this.alert.seen;
        }
    },

    methods: {
        afterEnter: function () {
            console.log('afterEnter called!' + 'timeout = ' + this.alert.timeout);
            let alertEl = jQuery('#'+this.alert.alertId);
            setTimeout(function(){
                alertEl.alert('close');
            },this.alert.timeout);
        }
    }

}
</script>

