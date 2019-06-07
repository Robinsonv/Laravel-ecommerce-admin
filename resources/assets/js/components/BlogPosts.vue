<template>
    <div class="blog-section">
        <div class="container">
            <h1 class="text-center">From Our Blog</h1>

            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore vitae nisi, consequuntur illum dolores cumque pariatur quis provident deleniti nesciunt officia est reprehenderit sunt aliquid possimus temporibus enim eum hic.</p>

            <div class="blog-posts">


                <div v-for="post in posts" class="blog-post" :key="post.id">
                    <!-- <a :href="post.link"><img src="/img/blog1.png" alt="Blog Image"></a> -->

                    <a :href="post.link">
                        <!-- verificar si existe la key wp:featuredmedia antes de pasar la url -->
                        <blog-image
                            :url="post._links['wp:featuredmedia'][0].href">
                        </blog-image>
                    </a>
                        
                    <a :href="post.link"><h2 class="blog-title">{{ post.title.rendered }}</h2></a>
                    <div class="blog-description">{{ stripTags(post.content.rendered) }}</div>
                </div>



                <!-- <div class="blog-post" id="blog2">
                    <a href="#"><img src="/img/blog2.png" alt="Blog Image"></a>
                    <a href="#"><h2 class="blog-title">Blog Post Title 2</h2></a>
                    <div class="blog-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi, tenetur numquam ipsam reiciendis.</div>
                </div>
                <div class="blog-post" id="blog3">
                    <a href="#"><img src="/img/blog3.png" alt="Blog Image"></a>
                    <a href="#"><h2 class="blog-title">Blog Post Title 3</h2></a>
                    <div class="blog-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi, tenetur numquam ipsam reiciendis.</div>
                </div> -->
            </div>
        </div> <!-- end container -->
    </div> <!-- end blog-section -->
</template>

<script>

import BlogImage from "./BlogImage"
import sanitizeHtml from "sanitize-html"
export default {
    components: {
        BlogImage,
    },
    created() {
        
        const URL = 'http://localhost/wordpress/index.php/wp-json/wp/v2/posts';

        axios.get(URL)
            .then(resp => {
                console.log("See info  >>>>>>>>> ", resp)
                this.posts = resp.data

            })
    },
    data() {
        return {
            posts: []
        }
    },
    methods: {
        stripTags(html){
            return sanitizeHtml(html, {
                allowedTags: [] 
                })
        }
    }
}
</script>
