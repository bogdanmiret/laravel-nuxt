<template>
    <div class="widget">
        <div class="user-photo border">
            <div class="form-group">
                <label>File</label>
                <div class="user-photo-action" id="pick-media" v-text="'Choose file'" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; cursor: pointer;"></div>
            </div>

            <avatar-cropper @uploaded="handleUploaded"
                            @submit="submited"
                            :cropperOptions="{ aspectRatio: 0 }"
                            :output-options="{width: auto, height: auto}"
                            trigger="#pick-media"
                            :upload-url="upload_url"
                            :upload-headers="{'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN' : getCsrfToken()}"
                            :labels="{submit : translates.submit, cancel: translates.cancel}"
            />
        </div>
    </div>
</template>

<script>
    import AvatarCropper from "vue-avatar-cropper";

    export default {
        props: ["upload_url", "translates"],
        components: { AvatarCropper },
        methods: {
            handleUploaded(resp) {
                window.location.replace('/admin/media');
            },
            submited() {
                iziToast.show({
                    title: 'Processing',
                    color: 'green' // blue, red, green, yellow
                });
            },

            getCsrfToken() {
                return $('meta[name="csrf-token"]').attr('content');
            }
        },
    };
</script>

<style>
</style>
