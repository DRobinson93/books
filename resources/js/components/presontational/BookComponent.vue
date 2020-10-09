<template>
    <li class="hover:shadow-lg border border-gray-400 p-2 bg-gradient-to-t via-gray-100">
        <div class="flex rounded cursor-move">
            <Badge :txt="rank"/>
            <span class="flex-1 self-center pl-1 ">
                {{bookData.title}}
            </span>
            <span class="flex-1 ">
                <button class="dr-btn-red float-right" @click="$emit('remove')">
                    <icon name="trash" class="w-6 h-6" />
                </button>
                <button class="dr-btn-blue float-right mr-2" @click="showInfo=!showInfo">
                    <icon name="information-circle" class="w-6 h-6" />
                </button>
            </span>
        </div>
        <transition name="fade">
            <div v-if="showInfo">
                <div class="grid grid-flow-col grid-cols-2 border-t">
                    <a :href="bookData.link" class="border-r border-b p-2 flex">
                        <div class="m-auto content-center no-underline hover:underline text-blue-500 text-lg">Preview</div>
                    </a>
                    <div class="border-l border-b p-2">
                        <h4>Author(s):</h4>
                        <div class="divide-y divide-gray-400">
                            <div class="py-2" v-for="(author, index) in bookData.authors" :key="index">{{author}}</div>
                        </div>
                    </div>
                </div>
                <p class="text-sm pt-2">{{bookData.description}}</p>
            </div>
        </transition>
    </li>
</template>
<script>
    import Badge from './Badge.vue';
    export default {
        data: function(){
            return {
                showInfo: false,
                bookData: {}
            }
        },
        components:{
            Badge
        },
        props: {
            apiId: String,
            rank:Number,
            resultsMode: {
                type: Boolean,
                default: false
            }
        },
        mounted(){
            axios.get('/book/'+this.apiId)
                .then((response) =>{
                    // handle success
                    this.bookData = response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
        }
    }
</script>
