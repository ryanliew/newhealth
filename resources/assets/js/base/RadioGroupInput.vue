<template>
	<div class="form-group">
		<template v-if="!hideLabel">
	        <label v-if="editable">{{ label }}</label>
	        <label class="label-small" v-else>{{ label }}</label>
	        <br>
	    </template>
        <div class="form-check form-check-inline" v-for="value in values" v-if="editable">
            <input class="form-check-input" :name="name" type="radio" :id="value" :value="value" @click="updateValue(value)" :checked="value == defaultValue">
            <label class="form-check-label" :for="value">{{ "input." + value | trans }}</label>
        </div>
        <div v-if="!editable">
        	{{ "input." + defaultValue | trans }}
        </div>
    </div>
</template>

<script>
	export default {
		props: { defaultValue: String, values: Array, label: String, name: String, hideLabel: { default: false }, editable: { default: true}},
		data() {
			return {

			};
		},

		methods: {
			updateValue(value) {
				this.$emit('input', value);
			}
		},
	}
</script>