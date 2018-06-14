<template>

	<div class="row">
		<div class="col-sm-12">
			<div class='card'>
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							{{ title | trans }}
						</div>
						<div class="col-auto">
							<button class="btn btn-primary" @click="back"><i class="fa fa-arrow-left"></i> {{ 'table.back' | trans }}</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form @submit.prevent="submit" 
						@keydown="form.errors.clear($event.target.name)" 
						@input="form.errors.clear($event.target.name)">
						<table style="width:1170px; padding: 10px;margin: 0 auto;">
							<tr style="background: black">
								<td style="padding-top:35px;">
									<b style="font-size:60px;color:white;margin-left: 30px;line-height: 44px;margin-top:15px;">{{ 'post.newleaf_bulletin' | trans }}</b>
								</td>
								<td></td>
							</tr>
							<tr style="background: black">
								<td style="padding-bottom:20px;">
									<span style="font-size:17px;color:white;margin-left: 31px;margin-bottom:15px;">{{ 'post.latest_news' | trans }}</span>
								</td>
								<td style="padding-bottom:20px;text-align:center;"> 
									
								</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom:30px;">
									<div class="custom-file-uploader" v-if="isEditing">
										<div class="options">
											<button type="button" class="btn btn-xs btn-primary" @click="openFileSelector('cover_photo')"><i class="fa fa-plus"></i></button>
										</div>
										<img :src="coverPhoto.src" width="100%" id="cover_photo_placeholder">
										<div class="image-input">
											<image-input v-model="coverPhoto" :defaultImage="coverPhoto"
												@loaded="coverPhotoLoaded"
												label="transLabelKey"
												name="cover_photo"
												:required="true"
												accept="image/*"
												:error="$options.filters.trans(form.errors.get('cover_photo'))">
											</image-input>
										</div>
									</div>
									<img :src="coverPhoto.src" width="100%" v-else>

								</td>
							</tr>
						</table>
						<table style="width:1170px; padding: 10px;margin: 0 auto;">
							<tr style="vertical-align: middle;">
								<td style="width:45%;">
									<div style="padding-bottom:30px;border-bottom: 10px solid black;margin-bottom: 30px;">
										<template v-if="isEditing">
											<text-input v-model="form.title" 
												:defaultValue="form.title"
												:required="true"
												type="text"
												label="English title"
												name="title"
												:editable="user.is_admin"
												:focus="false"
												:hideLabel="false"
												:error="form.errors.get('title')">
											</text-input>
											<hr>
											<text-input v-model="form.title_zh" 
												:defaultValue="form.title_zh"
												:required="true"
												type="text"
												label="Chinese title"
												name="title"
												:editable="user.is_admin"
												:focus="false"
												:hideLabel="false"
												:error="form.errors.get('title')">
											</text-input>
										</template>
										<b style="font-size: 45px;line-height:45px;" v-else>
											{{ postTitle }}
										</b>
									</div>
									<div class="custom-file-uploader" v-if="isEditing">
										<div class="options">
											<button type="button" class="btn btn-xs btn-primary" @click="openFileSelector('left_photo')"><i class="fa fa-plus"></i></button>
										</div>
										<img :src="leftPhoto.src" width="100%" id="left_photo_placeholder">
										<div class="image-input">
											<image-input v-model="leftPhoto" :defaultImage="leftPhoto"
												@loaded="leftPhotoLoaded"
												label="transLabelKey"
												name="left_photo"
												:required="true"
												accept="image/*"
												:error="$options.filters.trans(form.errors.get('left_photo'))">
											</image-input>
										</div>
									</div>
									<img :src="leftPhoto.src" width="100%" v-else>
								</td>
								<td>
									<div style="padding: 50px">
										<template v-if="isEditing">
											<label>English content</label>
											<vue-editor id="english" v-model="form.content" :editorToolbar="customToolbar"></vue-editor>
											<hr>
											<label>Chinese content</label>
											<vue-editor id="chinese" v-model="form.content_zh" :editorToolbar="customToolbar"></vue-editor>
										</template>
										<template v-else>
											<div v-html="form.content" v-if="lang.locale == 'en'"></div>
											<div v-html="form.content_zh" v-else></div>
										</template>
									</div>
								</td>
							</tr>
						</table>					
							
						<button type="submit" class="btn btn-success mt-3" :disabled="form.submitting" v-html="submitButtonContent" v-if="isEditing"></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { VueEditor } from 'vue2-editor';
	import formButtonMixin from '../mixins/form-button.js';
	import moment from 'moment';

	export default {
		props: ['selectedPost', 'isEditing'],

		components: { VueEditor },

		mixins: [formButtonMixin],

		data() {
			return {
				form: new Form({
					title: '',
					content: '',
					user: '',
					title_zh: '',
					content_zh: '',
					cover_photo: '',
					left_photo: ''
				}),
				user: window.user,
				lang: lang,
				customToolbar: [
					['bold', 'italic', 'underline', 'strike'],
					['blockquote', 'code-block'],
					[{ 'header': 1 }, { 'header': 2 }],   
					[{ 'list': 'ordered'}, { 'list': 'bullet' }],
					[{ 'indent': '-1'}, { 'indent': '+1' }],  
					[{ 'size': ['small', false, 'large', 'huge'] }],
					[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
					['hyperlink']
				],
				coverPhoto: {name: 'No file selected', src: '/img/select-image.png'},
				leftPhoto: {name: 'No file selected', src: '/img/select-image.png'}
			};
		},

		mounted() {
			if(this.selectedPost) {
				this.form.title = this.selectedPost.title;
				this.form.content = this.selectedPost.content;
				this.form.content_zh = this.selectedPost.content_zh;
				this.form.title_zh = this.selectedPost.title_zh;
				this.form.cover_photo = this.selectedPost.cover_photo;
				this.form.left_photo = this.selectedPost.left_photo;

				this.coverPhoto.src = 'storage/' + this.selectedPost.cover_photo;
				this.leftPhoto.src = 'storage/' + this.selectedPost.left_photo;
			}
		},

		methods: {
			
			back() {
				this.$emit('back');
			},

			submit() {
				this.form.user = this.user.id;
				this.form.post(this.url)
					.then(response => this.onSuccess(response));
			},

			onSuccess(response) {
				flash(this.$options.filters.trans(response.message));
				this.back();
			},

			coverPhotoLoaded(e) {
				this.coverPhoto = { src: e.src, file: e.file };
				this.form.cover_photo = e.file;
				this.form.errors.clear('cover_photo');
			},

			leftPhotoLoaded(e) {
				this.leftPhoto = { src: e.src, file: e.file };
				this.form.left_photo = e.file;
				this.form.errors.clear('left_photo');
			},

			openFileSelector(selector) {
				document.getElementById(selector).click();
			}
		},

		computed: {
			url() {
				return this.selectedPost ? '/api/admin/post/' + this.selectedPost.id + '/update' : '/api/admin/posts';
			},

			title() {
				if(this.selectedPost)
				{
					return this.isEditing ? 'post.edit_post' : this.postTitle;
				}

				return 'post.add_new_post';
			},

			postTitle() {
				return this.lang.locale == 'en' ? this.selectedPost.title : this.selectedPost.title_zh;
			},

			date() {
				return this.selectedPost ? moment(this.selectedPost.updated_at).format("Do MMMM YYYY") : moment().format("Do MMMM YYYY");
			}
		}	
	}
</script>