<template>
    <div class="container p-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div class="col-span-1">
                <div v-if="bookApiIds.length === 0">
                    <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                        <icon name="information-circle" class="fill-current w-4 h-4 mr-2" />
                        <p>Search and add a book for results to appear here.
                            Once books are added, drag and drop to update their order.</p>
                    </div>
                </div>
                <ul class="">
                    <draggable v-model="bookApiIds" group="people" @end="orderUpdated">
                        <BookComponent v-for="(api_id, index) in bookApiIds" :rank="index + 1"
                                       :key="api_id" :api-id="api_id" @remove="remove(index)" />
                    </draggable>
                </ul>
            </div>
            <div class="col-span-1">
                <label>
                    <input class="dr-input" v-model="bookTitle" type="text" placeholder="Enter a book title">
                </label>
                <div class="divide-y divide-gray-400" v-if="apiSearchResults.length">
                    <div class="py-2 flex" v-for="(result) in apiSearchResults" :key="result.id" v-if="!bookApiIds.includes(result.id)">
                        <div class="self-center">{{result.title}}</div>
                        <button class="dr-btn-blue  ml-auto mr-2" @click="add(result.id)">
                            <icon name="plus-circle" class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import BookComponent from './presontational/BookComponent.vue'
    const axios = require('axios');
    export default {
        components: {
            draggable, BookComponent
        },
        data: function () {
            return {
                bookTitle: '',
                bookApiIds: [],
                apiSearchResults:[]
            }
        },
        methods: {
            add(apiId) {
                this.bookApiIds.push(apiId);
                this.store();
            },
            remove(index) {
                this.bookApiIds.splice(index, 1);
                this.store();
            },
            orderUpdated () {
                this.store();
            },
            store(){
                axios.post('/book', {
                    apiIds: this.bookApiIds
                })
                .then((response) =>{
                    this.$notify({
                        group: 'foo',
                        title: 'Congrats!',
                        type:'info',
                        text: 'Books updated'
                    });
                })
                .catch((error) => {
                    this.displayGeneralErrorMsg();
                });
            },
            displayGeneralErrorMsg(){
                this.$notify({
                    group: 'foo',
                    title: 'Ops!',
                    type:'error',
                    text: 'An error occurred'
                });
            }
        },
        watch: {
            // whenever question changes, this function will run
            bookTitle: function (newBookTitle) {
                if(newBookTitle === "")
                    return;
                axios.get('/book/search/'+newBookTitle)
                    .then((response) =>{
                        // handle success
                        this.apiSearchResults = response.data;
                    })
                    .catch((error) => {
                        this.displayGeneralErrorMsg();
                    })
            }
        },
        mounted() {
            axios.get('/book')
                .then((response) =>{
                    // handle success
                    this.bookApiIds = response.data;
                })
                .catch((error) => {
                    // handle error
                    this.displayGeneralErrorMsg();
                })
        }
    }
</script>
