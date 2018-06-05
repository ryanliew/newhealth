<template>
	<div class="form-group">
        <label>{{ label }}</label><br>
        <div class="form-check form-check-inline" v-for="value in values">
            <input class="form-check-input" :name="name" type="radio" :id="value" :value="value" @click="updateValue(value)" :checked="value == defaultValue">
            <label class="form-check-label" :for="value">{{ "input." + value | trans }}</label>
        </div>
    </div>
</template>

<script>
	export default {
		props: ['defaultValue', 'values', 'label', 'name'],
		data() {
			return {

			};
		},

		methods: {
			updateValue(value) {
				this.$emit('input', value);
			}
		}	
	}
</script>