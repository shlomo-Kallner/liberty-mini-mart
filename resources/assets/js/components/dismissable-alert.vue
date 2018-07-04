<template>
    <div v-if="isSeen" v-bind:id="alert.alertId" v-bind:class="usedCssClasses" >
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
        <strong><span v-html="alert.title"></span></strong> 
        <span v-html="alert.content"></span>
    </div>
</template>

<script>
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
            return "alert" + this.alert.cssClasses.trim() + "alert-dismissible fade in";
        },
        isSeen: function() {
            return this.alert.seen;
        }
    }

}
</script>

