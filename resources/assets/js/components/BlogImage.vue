<template>
    <img :src="imageSrc" alt="Blog Image">
</template>
<script>
export default {
    props: ['url'],
    
    created() {
        
        const URL = this.url;

        axios.get(URL)
            .then(resp => {
                console.log("See info image  >>>>>>>>> ", resp.data.media_details.sizes.medium.source_url)
                this.imageSrc = resp.data.media_details.sizes.medium.source_url

            })
    },
    data() {
        return {
            imageSrc: ''
        }
    },
    
}
</script>
