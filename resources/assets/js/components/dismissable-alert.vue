<template v-if="isSeen">
    <div v-bind:id="alert.alertId" v-bind:class="usedCssClasses" v-show="isSeen">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
        <strong><span v-html="alert.title"></span></strong> 
        <span v-html="alert.content"></span>
    </div>
</template>

<script>

import * as jQuery from 'jquery'; // 'jquery/dist/jquery'

export default {
    name: 'dismissable-alert',

    props: ['initAlert'], //['cssClasses', 'title', 'content', 'alertId', 'timeout' ],

    data: function () {
        return {
            alert: this.initAlert
        }
    }, 

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
        setAlertTimeout: function (name) {
            console.log(name + ' called! timeout = ' + this.alert.timeout);
            if (this.alert.timeout) {
                let alertEl = window.jQuery('#' + this.alert.alertId);
                setTimeout(function () {
                    alertEl.alert('close');
                }, this.alert.timeout);
            }
        }
    },

    mounted: function () {
        this.setAlertTimeout('mounted');
    },
    updated: function () {
        this.setAlertTimeout('updated');
    }

}
</script>

