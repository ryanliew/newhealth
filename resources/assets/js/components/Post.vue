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
						@input="form.errors.clear($event.target.name)"
						v-if="isEditing">

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

						<label>English content</label>
						<vue-editor id="english" v-model="form.content"></vue-editor>

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

						<label>Chinese content</label>
						<vue-editor id="chinese" v-model="form.content_zh"></vue-editor>
							
						<button type="submit" class="btn btn-success mt-3" :disabled="form.submitting" v-html="submitButtonContent"></button>
					</form>
					<div v-else>
						<div v-html="form.content" v-if="lang.locale == 'en'"></div>
						<div v-html="form.content_zh" v-else></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { VueEditor } from 'vue2-editor';
	import formButtonMixin from '../mixins/form-button.js';

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
					content_zh: ''
				}),
				user: window.user,
				lang: lang
			};
		},

		mounted() {
			if(this.selectedPost) {
				this.form.title = this.selectedPost.title;
				this.form.content = this.selectedPost.content;
				this.form.content_zh = this.selectedPost.content_zh;
				this.form.title_zh = this.selectedPost.title_zh;
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
			}
		}	
	}
</script>