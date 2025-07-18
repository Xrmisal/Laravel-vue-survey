<script setup>
import PageComponent from '../components/PageComponent.vue';
import QuestionEditor from '../components/editor/QuestionEditor.vue';

import { v4 as uuidv4 } from "uuid"
import { ref, watch, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import {useStore} from "vuex"

const store = useStore()
const router = useRouter();
const route = useRoute();
const surveyLoading = computed(() => store.state.currentSurvey.loading)
// Create empty survey
let model = ref({
    title: "",
    status: false,
    description: null,
    image: null,
    image_url: null,
    expire_date: null,
    questions: [],
});

// Watch for current survey change and update local model upon change
watch(
// What is watched
() => store.state.currentSurvey.data,
// What to do when changes are made
(newVal, oldVal) => {
    model.value = {
        ...JSON.parse(JSON.stringify(newVal)),
        status: newVal.status,
    }
}
)

if (route.params.id) {
    store.dispatch('getSurvey', route.params.id)
}

function onImageChoose(ev) {
    const file = ev.target.files[0];
    
    const reader = new FileReader();
    reader.onload = () => {
        // The field to send on backend
        model.value.image = reader.result;
        
        // The field to display here
        model.value.image_url = reader.result
    }
    reader.readAsDataURL(file);
}

function addQuestion(index) {
    const newQuestion = {
        id: uuidv4(),
        type: "text",
        question: "",
        description: null,
        data: {},
    }
    
    model.value.questions.splice(index, 0, newQuestion);
}

function deleteQuestion(question) {
    model.value.questions = model.value.questions.filter((q) => q !== question)
}

function questionChange(question) {
    model.value.questions = model.value.questions.map(
    (q) => {
        if (q.id === question.id) {
            return JSON.parse(JSON.stringify(question))
        }
        return q
    } 
    )
}

function saveSurvey() {
    store.dispatch("saveSurvey", model.value).then(({data}) => {
        store.commit("notify", {
            type: 'success',
            message: 'Survey was successfully updated'
        })
        router.push({
            name: "SurveyView",
            params: {id: data.data.id}
        })
    })
}

function deleteSurvey() {
    if(confirm(`Are you sure you want to delete this survey? This action can't be undone`)) {
        store.dispatch("deleteSurvey", model.value.id)
            .then(() => {
                router.push({
                    name: "Surveys"
                })
            })
    }
}

</script>

<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ route.params.id ? model.title : "Create a Survey" }}
                </h1>
                <button
                v-if="route.params.id"
                type="button"
                @click="deleteSurvey()"
                class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-300"
                >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mt-1 mr-0.5 mb-0.5 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>                
                Delete Survey
            </button>
        </div>
    </template>
    <div v-if="surveyLoading" class="flex justify-center">Loading...</div>
    <form v-else @submit.prevent="saveSurvey" class="animate-fade-in-down">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <!-- Survey Field-->
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">
                        Image
                    </label>
                    <div class="mt-1 flex items-center">
                        <img 
                        v-if="model.image_url"
                        :src="model.image_url" 
                        :alt="model.title"
                        class="w-64 h-48 object-cover"
                        />
                        <span
                        v-else
                        class="
                                    flex
                                    items-center
                                    justify-center
                                    h-12
                                    w-12
                                    rounded-full
                                    overflow-hidden
                                    bg-gray-100
                                "
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-[80%] w-[80%] text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg> 
                    </span>
                    <button 
                    type="button"
                    class="
                                relative
                                overflow-hidden
                                ml-5
                                bg-white
                                py-2
                                px-3
                                border border-gray-300
                                rounded-md
                                shadow-sm
                                text-sm
                                leading-4
                                font-medium
                                text-gray-700
                                hover:bg-gray-50
                                focus:outline-none
                                focus:ring-2
                                focus:ring-offset-2
                                focus:ring-indigo-500
                            "
                    >
                    <input type="file"
                    id="image"
                    @change="onImageChoose"
                    class="
                                absolute
                                left-0
                                top-0
                                right-0 
                                bottom-0
                                opacity-0
                                cursor-pointer

                            "
                    />
                    Change
                </button>
            </div>
        </div>
        <!--/ Image -->
        
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
                Title
            </label>
            <input type="text" 
            name="title"
            id="title"
            v-model="model.title"
            autocomplete="survey_title"
            class="
                            mt-1
                            mb-2
                            h-8
                            p-2
                            focus:ring-indigo-500 focus:border-indigo-500
                            block
                            font-medium
                            w-full
                            shadow-sm
                            text-sm
                            border-gray-300
                            rounded-md
                        "
            placeholder="Enter title here"
            />
        </div>
        <!--/ Title -->
        
        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">
                Description
            </label>
            <div class="mt-1">
                <textarea name="description" 
                id="description"
                rows="3"
                v-model="model.description"
                autocomplete="survey_description"
                class="
                            p-2
                            shadow-sm
                            focus:ring-indigo-500 focus:border-indigo-500
                            mt-1
                            block
                            w-full
                            text-sm
                            border border-gray-300
                            rounded-md
                        "
                placeholder="Describe your survey">
            </textarea>
        </div>
    </div>
    <!-- / Description -->
    
    <!-- Expire Date -->
    <div>
        <label for="expire_date" class="block text-sm font-medium text-gray-700">
            Expire Date
        </label>
        <input type="date" 
        name="expire_date"
        id="expire_date"
        v-model="model.expire_date"
        class="
                            p-2
                            mt-1
                            focus:ring-indigo-500 focus:border-indigo-500
                            block
                            w-full
                            shadow-sm
                            sm:text-sm
                            border-gray-300
                            rounded-md
                        "
        />
    </div>
    <!--/ Expire Date -->
    
    <!-- Status -->
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input type="checkbox"
            id="status"
            name="status"
            v-model="model.status"
            :true-value="true"
            :false-value="false"
            class="
                                focus:ring-indigo-500
                                h-4
                                w-4
                                text-indigo-600
                                border-gray-300
                                rounded
                            "
            >
        </div>
        <div class="ml-3 text-sm">
            <label for="status" class="font-medium text-gray-700">Active</label>
        </div>
    </div>
    <!--/ Status -->
</div>
<!--/ Survey Fields-->
<div class="px-4 py-5 bg-white space-y-6 sm:p-6">
    <h3 class="text-2xl font-semibold flex items-center justify-between">
        Questions
        
        <button
        type="button"
        @click="addQuestion()"
        class="
                            flex
                            items-center
                            text-sm
                            py-1
                            px-4
                            rounded-sm
                            text-white
                            bg-gray-600
                            hover:bg-gray-700
                        "
        >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Add Question
    </button>
</h3>
<div v-if="!model.questions.length" class="text-center text-gray-600">
    You don't have any questions created
</div>
<div v-for="(question, index) in model.questions" :key="question.id">
    <QuestionEditor 
    :question="question"
    :index="index"
    @change="questionChange"
    @addQuestion="addQuestion"
    @deleteQuestion="deleteQuestion"
    />
    
</div>
</div>

<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
    <button
    type="submit"
    class="
                        inline-flex
                        justify-center
                        py-2
                        px-4
                        border border-transparent
                        shadow.sm
                        text-sm
                        font-medium
                        rounded-md
                        text-white
                        bg-indigo-600
                        hover:bg-indigo-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-indigo-500
                    "
    >
    Save
</button>
</div>
</div>
</form>
</PageComponent>
</template>