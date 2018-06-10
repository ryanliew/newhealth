<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<table-view ref="posts"
						:fields="fields"
						:title="$options.filters.trans('post.posts')"
						:url="url"
						:searchables="searchables"
						:dateFilterable="false"
						:filterMonth="filterMonth"
						:addNew="canAddNew"
						@back="back"
						v-if="!isViewing && !isEditing">
			</table-view>
			<post :selectedPost="selectedPost" :isEditing="isEditing" v-else @back="back"></post>
		</transition>
	</div>
</template>

<script>
	import Post from '../components/Post.vue';

	export default {
		props: ['userId', 'filterMonth'],

		components: { Post },

		data() {
			return {
				fields: [
					{ name: 'created_at', title: this.tableTitle('post.created_at'), sortField: 'created_at', callback: 'date' },
					{ name: 'updated_at', title: this.tableTitle('post.last_update'), sortField: 'updated_at', callback: 'date'},
					{ name: '__component:post-title-switcher', title: this.tableTitle('post.title') },
					{ name: '__component:posts-actions', title: this.tableTitle('table.actions') }
				],
				searchables: "title,content,title_zh,content_zh",
				isEditing: false,
				isViewing: false,
				selectedPost: '',
				user: window.user
			};
		},

		mounted() {
			window.events.$on('add_new', function(){
				this.addNewPost();
			}.bind(this));

			this.$events.on('view', data => this.view(data));
			this.$events.on('edit', data => this.edit(data));
		},

		methods: {

			tableTitle(value) {
				return this.$options.filters.trans(value);
			},

			back() {
				this.selectedPost = '';
				this.isViewing = false;
				this.isEditing = false;
			},

			view(data) {
				this.selectedPost = data;
				this.isViewing = true;
			},

			addNewPost() {
				this.isEditing = true;
			},

			edit(data) {
				this.selectedPost = data;
				this.isEditing = true;
			}

		},

		computed: {
			url() {
				let id = this.userId ? this.userId : window.user.id;

				return "/api/posts";
			},

			canAddNew() {
				return this.user.is_admin ? 'post.add_new_post' : '';
			}
		}
	}
</script>