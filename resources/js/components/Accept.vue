<template>
	<div>
		<a v-if="canAccept" :class="classes" 
			title="Mark the answer as best" 
			@click.prevent="create"
			>
			<i class="fas fa-check fa-2x"></i>
		</a>

		<a v-if="accepted" :class="classes" title="the question owner accepted this answer as best answer">
			<i class="fas fa-check fa-2x"></i>
		</a>
	</div>
</template>

<script>
import EventBus from '../event-bus';
export default {
	props: ['answer'],

	data () {
		return {
			isBest: this.answer.is_best,
			id: this.answer.id
		};
	},
	created () {
		EventBus.$on('accepted', id=> {
			this.isBest = (id===this.id);
		});
	},

	methods: {
		create () {
			axios.post(`/answers/${this.id}/accept`)
			.then(res => {
				this.$toast.success(res.data.message, "Success", {
					timeout:2000,
					position: 'bottomLeft'
				});
				this.isBest = true;
				EventBus.$emit('accepted', this.id);
			})
		}
	},


	computed: {
		canAccept () {
			return this.authorize('accept', this.answer);
		},

		accepted () {
			return !this.canAccept && this.isBest;
		},

		classes () {
			return [
				'mt-2',
				this.isBest ? 'vote-accepted' : ''
				]
		}
	}
}
</script>