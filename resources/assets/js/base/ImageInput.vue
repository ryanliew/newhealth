<template>
	<div class="form-group mb-3">
		<label>{{ label | trans }} <span class="text-danger" v-if="required">*</span></label>
		<div class="input-group">
			<div class="input-group-prepend">
				<a class="btn btn-primary" @click="toggleImageInputType">
					<i class="fa" :class="iconClass"></i>
				</a>
			</div>
			<div class="custom-file">
			    <input type="file" :accept="accept" class="custom-file-input" :id="name" @change="onChange" v-if="!camera">
			    <input type="file" :accept="accept" class="custom-file-input" capture="camera" :id="name" @change="onChange" v-else>
			    <label class="custom-file-label" :for="name">{{ inputLabel | trans }}</label>
			</div>

		</div>
		<span class="text-muted">{{ 'input.switch_file_mode' | trans }}</span>
	  	<span class="text-danger" v-if="error" v-text="error"></span>
		<img :src="defaultImage.src" class="img-thumbnail" v-if="defaultImage.src && (defaultImage.src.endsWith('.jpg') || defaultImage.src.endsWith('.png') || defaultImage.src.endsWith('.jpeg'))">
		<img :src="defaultImage.src" class="img-thumbnail" v-if="defaultImage.file && defaultImage.file.type.match('^image.*')">
		
	 </div>
</template>

<script>
	export default {
		props: ['label', 'name', 'required', 'defaultImage', 'error', 'accept'],
		data() {
			return {
				camera: false
			};
		},
		methods: {
			onChange(e) {
				if( ! e.target.files.length ) return;

				let file = e.target.files[0];

				let reader = new FileReader();

				reader.readAsDataURL(file);

				reader.onload = e => {
					let src = e.target.result;

					this.$emit('loaded', { src, file });
				};	
			},

			toggleImageInputType() {
				this.camera = !this.camera;
			}
		},

		computed: {
			fileName() {
				return this.defaultImage.name;
			},

			iconClass() {
				return this.camera ? "fa-file" : "fa-camera";
			},

			inputLabel() {
				return this.camera ? 'input.take_from_camera' : 'input.choose_file';
			}
		}	
	}
</script>