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
				<div class="card-body text-center">
					<form @submit.prevent="submit" 
						@keydown="form.errors.clear($event.target.name)" 
						@input="form.errors.clear($event.target.name)">
						<table style="width:1170px; padding: 10px;margin: 0 auto;">
							<tr style="background: black">
								<td colspan="2">
					                <img src="/img/mail/newsletter-header.png" width="100%">
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
							<tr>
								<td>
									<b>{{ 'post.published_date' | trans }}: {{ date }}</b>
								</td>
							</tr>
						</table>
						<table style="width:1170px; padding: 10px;margin: 0 auto;">
							<tr>
								<td>
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
									<b style="font-size: 35px;line-height:35px;" v-else>
										{{ postTitle }}
									</b>
								</td>
							</tr>
						</table>
						<table style="height:25px">
							<tr>
								<td>
								</td>
							</tr>
						</table>
						<table :style="'width:' + thumbnailTableWidth + 'px; padding: 10px;margin: 0 auto;'">
							<tr style="vertical-align: middle;">
								<td style="padding:10px;">
									<table v-if="isEditing || leftPhoto.src !== '/img/select-image.png'">
										<tr>
											<td>
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
										</tr>
										<tr>
											<td>
												<template v-if="isEditing">
													<text-input v-model="form.left_caption" 
														:defaultValue="form.left_caption"
														:required="true"
														type="text"
														label="English caption"
														name="left_caption"
														:editable="user.is_admin"
														:focus="false"
														:hideLabel="false"
														:error="form.errors.get('left_caption')">
													</text-input>
													<text-input v-model="form.left_caption_zh" 
														:defaultValue="form.left_caption_zh"
														:required="true"
														type="text"
														label="Chinese caption"
														name="left_caption_zh"
														:editable="user.is_admin"
														:focus="false"
														:hideLabel="false"
														:error="form.errors.get('left_caption_zh')">
													</text-input>
												</template>
												<i v-else>
													{{ leftCaption }}
												</i>
											</td>
										</tr>
									</table>
								</td>
								<td style="padding:10px;">
									<table v-if="isEditing || middlePhoto.src !== '/img/select-image.png'">
										<tr>
											<td>
												<div class="custom-file-uploader" v-if="isEditing">
													<div class="options">
														<button type="button" class="btn btn-xs btn-primary" @click="openFileSelector('left_photo')"><i class="fa fa-plus"></i></button>
													</div>
													<img :src="middlePhoto.src" width="100%" id="middle_photo_placeholder">
													<div class="image-input">
														<image-input v-model="middlePhoto" :defaultImage="middlePhoto"
															@loaded="middlePhotoLoaded"
															label="transLabelKey"
															name="middle_photo"
															:required="true"
															accept="image/*"
															:error="$options.filters.trans(form.errors.get('middle_photo'))">
														</image-input>
													</div>
												</div>
												<img :src="middlePhoto.src" width="100%" v-else>
											</td>
										</tr>
										<tr>
											<td>
												<template v-if="isEditing">
													<text-input v-model="form.middle_caption" 
														:defaultValue="form.middle_caption"
														:required="true"
														type="text"
														label="English caption"
														name="middle_caption"
														:editable="user.is_admin"
														:focus="false"
														:hideLabel="false"
														:error="form.errors.get('middle_caption')">
													</text-input>
													<text-input v-model="form.middle_caption_zh" 
														:defaultValue="form.middle_caption_zh"
														:required="true"
														type="text"
														label="Chinese caption"
														name="middle_caption_zh"
														:editable="user.is_admin"
														:focus="false"
														:hideLabel="false"
														:error="form.errors.get('middle_caption_zh')">
													</text-input>
												</template>
												<i v-else>
													{{ middleCaption }}
												</i>
											</td>
										</tr>
									</table>
								</td>
								<td style="padding:10px;">
									<table v-if="isEditing || rightPhoto.src !== '/img/select-image.png'">
										<tr>
											<td>
												<div class="custom-file-uploader" v-if="isEditing">
													<div class="options">
														<button type="button" class="btn btn-xs btn-primary" @click="openFileSelector('right_photo')"><i class="fa fa-plus"></i></button>
													</div>
													<img :src="rightPhoto.src" width="100%" id="right_photo_placeholder">
													<div class="image-input">
														<image-input v-model="rightPhoto" :defaultImage="rightPhoto"
															@loaded="rightPhotoLoaded"
															label="transLabelKey"
															name="right_photo"
															:required="true"
															accept="image/*"
															:error="$options.filters.trans(form.errors.get('right_photo'))">
														</image-input>
													</div>
												</div>
												<img :src="rightPhoto.src" width="100%" v-else>
											</td>
										</tr>
										<tr>
											<td>
												<template v-if="isEditing">
													<text-input v-model="form.right_caption" 
														:defaultValue="form.right_caption"
														:required="true"
														type="text"
														label="English caption"
														name="right_caption"
														:editable="user.is_admin"
														:focus="false"
														:hideLabel="false"
														:error="form.errors.get('right_caption')">
													</text-input>
													<text-input v-model="form.right_caption_zh" 
														:defaultValue="form.right_caption_zh"
														:required="true"
														type="text"
														label="Chinese caption"
														name="right_caption_zh"
														:editable="user.is_admin"
														:focus="false"
														:hideLabel="false"
														:error="form.errors.get('right_caption_zh')">
													</text-input>
												</template>
												<i v-else>
													{{ rightCaption }}
												</i>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table style="width:1170px; padding: 10px;margin: 0 auto;">
							<tr>
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
											<div style="font-size:17px;" v-html="form.content" v-if="lang.locale == 'en'"></div>
											<div style="font-size:17px;" v-html="form.content_zh" v-else></div>
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
					left_photo: '',
					middle_photo: '',
					right_photo: '',
					left_caption: '',
					middle_caption: '',
					right_caption: '',
					left_caption_zh: '',
					middle_caption_zh: '',
					right_caption_zh: ''
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
				leftPhoto: {name: 'No file selected', src: '/img/select-image.png'},
				rightPhoto: {name: 'No file selected', src: '/img/select-image.png'},
				middlePhoto: {name: 'No file selected', src: '/img/select-image.png'}
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
				this.form.right_photo = this.selectedPost.right_photo;
				this.form.middle_photo = this.selectedPost.middle_photo;
				this.form.left_caption = this.selectedPost.left_caption;
				this.form.right_caption = this.selectedPost.right_caption;
				this.form.middle_caption = this.selectedPost.middle_caption;
				this.form.right_caption_zh = this.selectedPost.right_caption_zh;
				this.form.left_caption_zh = this.selectedPost.left_caption_zh;
				this.form.middle_caption_zh = this.selectedPost.middle_caption_zh;

				this.coverPhoto.src = 'storage/' + this.selectedPost.cover_photo;

				if(this.selectedPost.left_photo)
					this.leftPhoto.src = 'storage/' + this.selectedPost.left_photo;
				if(this.selectedPost.right_photo)
					this.rightPhoto.src = 'storage/' + this.selectedPost.right_photo;
				if(this.selectedPost.middlePhoto)
					this.middlePhoto.src = 'storage/' + this.selectedPost.middle_photo;
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

			middlePhotoLoaded(e) {
				this.middlePhoto = { src: e.src, file: e.file };
				this.form.middle_photo = e.file;
				this.form.errors.clear('middle_photo');
			},

			rightPhotoLoaded(e) {
				this.rightPhoto = { src: e.src, file: e.file };
				this.form.right_photo = e.file;
				this.form.errors.clear('right_photo');
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
				return this.lang == 'zh' ? this.selectedPost.title_zh : this.selectedPost.title;
			},

			leftCaption() {
				return this.lang == 'zh' ? this.selectedPost.left_caption_zh : this.selectedPost.left_caption;
			},

			rightCaption() {
				return this.lang == 'zh' ? this.selectedPost.right_caption_zh : this.selectedPost.right_caption;
			},

			middleCaption() {
				return this.lang == 'zh' ? this.selectedPost.middle_caption_zh : this.selectedPost.middle_caption;
			},

			date() {
				return this.selectedPost ? moment(this.selectedPost.updated_at).format("Do MMMM YYYY") : moment().format("Do MMMM YYYY");
			},

			thumbnailTableWidth() {
				let hasLeftPhoto = this.leftPhoto.src !== '/img/select-image.png' ? 1 : 0; 
				let hasMiddlePhoto = this.middlePhoto.src !== '/img/select-image.png' ? 1 : 0;
				let hasRightPhoto = this.rightPhoto.src !== '/img/select-image.png' ? 1 : 0; 
				return ( hasLeftPhoto + hasRightPhoto + hasMiddlePhoto ) * 390;
			}
		}	
	}
</script>