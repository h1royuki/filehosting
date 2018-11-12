<template>
    <div class = "col-lg-12" id="messageForm">
        <div class ="message-form" >
            <input type="text" v-model="comment.author" class="form-control form" placeholder="Enter your name">
            <textarea v-model="comment.message" placeholder="Enter message" class="form-control form" rows="3"></textarea>
            <div v-if="comment.message" v-bind:class="{red: comment.message.length > settings.message.length }"
                 class="message-length">{{ comment.message.length + '/ 200' }}</div>
            <button @click="sendComment" class ="button send-button">{{ reply.name ? 'Reply to ' + reply.name : 'Send' }}</button>
        </div>
        <div v-if="response" class ="comments">
            <div v-for="tree in response" class ="thread">
                <div class ="row">
                    <div class="col-1"> <user-icon class = "user-icon" />
                    </div>
                    <div class="col-10">
                        <div class ="comment">
                            <div class ="comment-author">{{ tree.parent.author }}</div>
                            <div class ="comment-date">{{ tree.parent.date | moment("D/M/Y, H:mm:ss") }}</div>
                            <div class ="comment-message">{{ tree.parent.message }}</div>
                            <a class ="reply-to" @click="replyTo(tree.parent.id, tree.parent.author)" href="#messageForm">Reply</a>
                        </div></div>
                </div>
                <div v-for ="child in tree.childs" class ="comment-child">
                    <div class ="row">
                        <div class="col-1">
                            <user-icon class = "user-child-icon" />
                        </div>
                        <div class="col-10">
                            <div class ="comment">
                                <div class ="comment-author">{{ child.author }}</div>
                                <div class ="comment-date">{{ child.date | moment("D/M/Y, H:mm:ss") }}</div>
                                <div class ="comment-message">{{ child.message }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserIcon from "vue-material-design-icons/AccountCircle";

    export default {

        data() {
            return {
                response: null,

                comment: {
                    author: null,
                    message: null,
                    parent_id: null
                },

                reply: {
                    name: null
                },

                settings: {
                    message: {
                        length: 200
                    },
                    author: {
                        length: 30
                    }
                }
            }
        },

        components: {
            'user-icon' : UserIcon,
        },

        created() {
            this.getComments();
        },

        methods: {
            validateForm() {

                if (this.comment.author && this.comment.author.length > this.settings.author.length) {
                    this.sendError('Name must not be longer than 30 characters');
                    return true;
                }

                if (!this.comment.message) {
                    this.sendError('Enter your message');
                    return true;
                }

                if (this.comment.message.length > this.settings.message.length) {
                    this.sendError('Message length should not exceed 200 characters');
                    return true;
                }

                return false;
            },

            getComments() {
                this.$http
                    .get('/comments/' + this.file_id)
                    .then((response) => {
                        this.response = response.data;
                    });
            },
            sendComment() {
                if (this.validateForm()) {
                    return;
                }

                this.$http
                    .post('/comments', {
                        file_id:  this.file_id,
                        author: this.comment.author,
                        message: this.comment.message,
                        parent_id: this.comment.parent_id
                    })
                    .then((response) => {
                        this.getComments();
                        this.clearForm();
                    });
            },

            clearForm() {
                for (let item in this.comment) {
                    this.comment[item] = null;
                }
                this.reply.name = null;
            },

            replyTo(id, name) {
                this.comment.parent_id = id;
                this.reply.name = name;

            },

        },

        props : ['file_id']
    }
</script>