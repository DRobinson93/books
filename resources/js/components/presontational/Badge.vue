<template functional>
    <span class="text-xs bg-gray-500 text-white rounded-full  h-4 w-4 flex justify-center">
        {{props.txt}}
    </span>
</template>

<script>
    export default {
        functional: true,
        name: 'Badge',
        props: ["txt"]
    }
</script>
