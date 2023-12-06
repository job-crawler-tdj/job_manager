<template>
    <Head title="Jobs title"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Jobs</h2>
        </template>
        <div class="py-12">
            <el-table :data="jobs" style="width: 100%">
                <el-table-column prop="source" label="Source"></el-table-column>
                <el-table-column prop="company" label="Company"></el-table-column>
                <el-table-column prop="job_name" label="Job Name"></el-table-column>
                <el-table-column prop="url" label="URL"></el-table-column>
                <el-table-column prop="min_monthly_salary" label="Min Monthly Salary"></el-table-column>
                <el-table-column prop="max_monthly_salary" label="Max Monthly Salary"></el-table-column>
                <el-table-column prop="min_annual_salary" label="Min Annual Salary"></el-table-column>
                <el-table-column prop="max_annual_salary" label="Max Annual Salary"></el-table-column>
                <el-table-column prop="rating" label="Rating"></el-table-column>
                <el-table-column prop="illegal" label="Illegal"></el-table-column>
                <el-table-column prop="comment" label="Comment"></el-table-column>
                <el-table-column prop="last_check_time" label="Last Check Time"></el-table-column>
                <el-table-column prop="last_seen" label="Last Seen"></el-table-column>
                <el-table-column prop="delivery_time" label="Delivery Time"></el-table-column>
                <el-table-column prop="starred" label="Starred"></el-table-column>
            </el-table>
            <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :page-sizes="[10, 30, 50, 100, 300]"
                :page-size="perPage"
                :page-count="Math.ceil(total / perPage)"
                layout="total, sizes, prev, pager, next, jumper"
                :total="total">
            </el-pagination>
        </div>
        <div class="py-12">
            <el-button @click="importJobs">Import jobs json data</el-button>
            <el-input
                v-model="importJson"
                :rows="5"
                type="textarea"
                placeholder="Please input"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    data() {
        return {
            importJson: '',
        }
    },
    methods: {
        handleCurrentChange(val) {
            window.location.href = "/jobs?perPage=" + this.perPage + "&page=" + val;
        },
        handleSizeChange(val) {
            window.location.href = "/jobs?perPage=" + val + "&page=" + this.currentPage;
        },
        importJobs() {
            fetch('/jobs', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    importJson: this.importJson
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    }
}
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";

const {jobs, total, perPage, currentPage} = defineProps(['jobs', 'total', 'perPage', 'currentPage']);

</script>
