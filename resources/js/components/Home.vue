<template>
    <v-container>
        <v-row align="top" no-gutters>
            <v-col md="5">
                <v-select
                    dense
                    :items="mediaNames"
                    label="サイト名"
                    outlined
                    v-model="mediaName"
                    @change="getArticles"
                ></v-select>
            </v-col>
            <v-col md="2" offset-md="5">
                <v-btn
                    depressed
                    large
                    color="primary"
                    @click="scrape"
                    :loading="scrapeLoading"
                >
                    サイト内容取得
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            mediaName: null,
            medias: [],
            articles: [],
            scrapeLoading: false,
        };
    },
    async mounted() {
        await this.getMedias();
        this.setMedias();
        this.getArticles();
    },
    computed: {
        mediaNames() {
            return this.medias.map((media) => media.name);
        },
    },
    methods: {
        async getMedias() {
            const response = await axios.get("/api/medias");
            this.medias = response.data;
        },
        setMedias() {
            this.mediaName = this.medias[0].name;
        },
        async getArticles() {
            const mediaId = this.medias.find(
                (media) => (media.name = this.mediaName)
            ).id;
            const response = await axios.get(`/api/medias/${mediaId}/articles`);
            this.articles = response.data;
            console.log(this.articles);
        },
        async scrape() {
            this.scrapeLoading = true;
            await axios.post("/api/scrape");
            this.scrapeLoading = false;
        },
    },
};
</script>

<style></style>
