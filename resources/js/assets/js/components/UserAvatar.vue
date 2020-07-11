<template>
    <div class="widget">
        <div class="user-photo border text-center" style="margin-top: 42px;">
            <img v-if="userAvatar" :src="userAvatar">
            <span class="user-photo-action" style="position: unset; margin-top: 20px;" id="pick-avatar" v-text="translates.reupload"></span>

            <avatar-cropper @uploaded="handleUploaded"
                            trigger="#pick-avatar" 
                            upload-url="/profile/avatar_upload"
                            :upload-headers="{'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN' : getCsrfToken()}" 
                            :labels="{submit : translates.submit, cancel: translates.cancel}"
            />
        </div>
    </div>
</template>

<script>
import AvatarCropper from "vue-avatar-cropper";

export default {
  props: ["avatar_url", "upload_url", "translates"],
  components: { AvatarCropper },
  data: function() {
    return {
      userAvatar: undefined
    };
  },
  methods: {
    handleUploaded(resp) {
      this.userAvatar = resp.relative_url;
    },

    getCsrfToken() {
      return window.Laravel.csrfToken;
    }
  },

  mounted: function() {
    this.userAvatar = this.avatar_url;
  }
};
</script>

<style>
</style>
