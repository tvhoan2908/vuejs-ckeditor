<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig" @ready="onEditorReady($event)"></ckeditor>
                    </div>
                    <div id="ckeditor"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import axios from "axios"
    export default {
        data() {
            return {
                instance: null,
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                },
                UploadAdapter: function (loader) {
                    this.loader = loader
                    this.upload = () => {
                        return this.loader.file.then(uploadedFile  => {
                            return new Promise( (resolve, reject) => {
                                const body = new FormData();
                                body.append('file', uploadedFile );
                                axios( {
                                    url: 'http://localhost:8000/upload',
                                    method: 'post',
                                    data: body,
                                    headers: {
                                        'Content-Type': 'multipart/form-data',
                                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                                    },
                                    withCredentials: false
                                } ).then( response => {
                                    if ( response.data.result == 'success' ) {
                                        resolve( {
                                            default: response.data.url
                                        } );
                                    } else {
                                        reject( response.data.message );
                                    }
                                } ).catch( response => {
                                    reject( 'Upload failed' );
                                } );
                            } )
                        })
                    }
                    this.abort = () => {
                        console.log('Abort upload.')
                    }
                }
            }
        },
        methods: {
            onEditorReady(editor) {
                editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                    // Configure the URL to the upload script in your back-end here!
                    return new this.UploadAdapter( loader );
                };
            }
        },
        mounted() {
            console.log('Component mounted.')
            // this.initialize()
        }
    }
</script>
