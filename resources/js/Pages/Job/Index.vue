<template>
    <Head title="Jobs title"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Jobs</h2>
        </template>
        <div class="py-12">
            <div style="color:white;">order by</div>
            <el-select v-model="condition.orderBy" placeholder="oder by"
                       @change="get"
                       clearable
            >
                <el-option label="id" value="id"/>
                <el-option label="last_check_time" value="last_check_time"/>
                <el-option label="delivery_time" value="delivery_time"/>
                <el-option label="rating" value="rating"/>
                <el-option label="min_monthly_salary" value="min_monthly_salary"/>
                <el-option label="max_monthly_salary" value="max_monthly_salary"/>
                <el-option label="min_annual_salary" value="min_annual_salary"/>
                <el-option label="max_annual_salary" value="max_annual_salary"/>
            </el-select>

            <div style="color:white;">order direction</div>
            <el-select v-model="condition.orderDirection" placeholder="oder direction"
                       @change="get"
                       clearable
            >
                <el-option label="desc" value="desc"/>
                <el-option label="asc" value="asc"/>
            </el-select>

            <div style="color:white;">if checked</div>
            <el-select v-model="condition.lastCheckTime" placeholder="Last Check Time"
                       @change="get"
                       clearable
            >
                <el-option label="unchecked" value="unchecked"/>
                <el-option label="checked" value="checked"/>
            </el-select>
            <div style="color:white;">if starred</div>
            <el-select v-model="condition.starred" placeholder="if starred"
                       @change="get"
                       clearable
            >
                <el-option label="false" value="false"/>
                <el-option label="true" value="true"/>
            </el-select>
            <div style="color:white;">if delivery</div>
            <el-select v-model="condition.ifDelivered" placeholder="if ifDelivery"
                       @change="get"
                       clearable
            >
                <el-option label="false" value="false"/>
                <el-option label="true" value="true"/>
            </el-select>
        </div>
        <div class="py-12">
            <el-table :data="jobs"
                      style="width: 100%"
                      :row-key="row => row.id">
                <el-table-column prop="id" label="id"></el-table-column>
                <el-table-column prop="source" label="Source"></el-table-column>
                <el-table-column prop="company" label="Company" min-width="150px"></el-table-column>
                <el-table-column prop="job_name" label="Job Name" min-width="150px">
                    <template #default="scope">
                        <template v-if="!scope.row.isEditing">
                            <a v-if="scope.row.url"
                               :href="scope.row.url" target="_blank"
                               style="color: #409eff; text-decoration: underline;">
                                {{ scope.row.job_name }}
                            </a>
                            <span v-else>
                                {{ scope.row.job_name }}
                            </span>
                        </template>

                        <div v-else>
                            <div>Job name</div>
                            <el-input
                                @change="updateRow(scope.row)"
                                v-model="scope.row.job_name"
                            ></el-input>

                            <div class="mt-2">Job url</div>
                            <el-input
                                @change="updateRow(scope.row)"
                                v-model="scope.row.url"
                            ></el-input>

                            <el-button @click="switchEditing(scope.row)">Cancel</el-button>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                    min-width="150px"
                    prop="min_monthly_salary"
                    label="min_monthly_salary"
                >
                    <template #default="scope">
                        <div @click="switchEditing(scope.row)" v-if="!scope.row.isEditing">
                            <span>{{ scope.row.min_monthly_salary ?? '---' }}</span>
                        </div>
                        <el-input
                            v-else
                            @change="updateRow(scope.row)"
                            v-model="scope.row.min_monthly_salary"
                            type="number"
                            :min="0"
                        ></el-input>
                    </template>
                </el-table-column>
                <el-table-column
                    min-width="150px"
                    prop="max_monthly_salary"
                    label="max_monthly_salary"
                >
                    <template #default="scope">
                        <div @click="switchEditing(scope.row)" v-if="!scope.row.isEditing">
                            <span>{{ scope.row.max_monthly_salary ?? '---' }}</span>
                        </div>
                        <el-input
                            v-else
                            @change="updateRow(scope.row)"
                            v-model="scope.row.max_monthly_salary"
                            type="number"
                            :min="0"
                        ></el-input>
                    </template>
                </el-table-column>
                <el-table-column
                    min-width="150px"
                    prop="min_annual_salary"
                    label="min_annual_salary"
                >
                    <template #default="scope">
                        <div @click="switchEditing(scope.row)" v-if="!scope.row.isEditing">
                            <span>{{ scope.row.min_annual_salary ?? '---' }}</span>
                        </div>
                        <el-input
                            v-else
                            @change="updateRow(scope.row)"
                            v-model="scope.row.min_annual_salary"
                            type="number"
                            :min="0"
                        ></el-input>
                    </template>
                </el-table-column>
                <el-table-column
                    min-width="150px"
                    prop="max_annual_salary"
                    label="max_annual_salary"
                >
                    <template #default="scope">
                        <div @click="switchEditing(scope.row)" v-if="!scope.row.isEditing">
                            <span>{{ scope.row.max_annual_salary ?? '---' }}</span>
                        </div>
                        <el-input
                            v-else
                            @change="updateRow(scope.row)"
                            v-model="scope.row.max_annual_salary"
                            type="number"
                            :min="0"
                        ></el-input>
                    </template>
                </el-table-column>
                <el-table-column prop="rating" label="Rating" min-width="150px">
                    <template #default="scope">
                        <el-rate v-model="scope.row.rating" show-score
                                 @change="updateRow(scope.row, 'rating')"
                        ></el-rate>
                    </template>
                </el-table-column>
                <el-table-column prop="starred" label="starred">
                    <template #default="scope">
                        <el-switch
                            :active-value="1"
                            :inactive-value="0"
                            @change="updateRow(scope.row)"
                            v-model="scope.row.starred"
                            class="ml-2"
                            style="--el-switch-on-color: #13ce66; --el-switch-off-color: #ff4949"
                        />
                    </template>
                </el-table-column>
                <el-table-column prop="starred" label="closed">
                    <template #default="scope">
                        <el-switch
                            :active-value="1"
                            :inactive-value="0"
                            @change="updateRow(scope.row)"
                            v-model="scope.row.closed"
                            class="ml-2"
                            style="--el-switch-on-color: #13ce66; --el-switch-off-color: #ff4949"
                        />
                    </template>
                </el-table-column>
                <el-table-column prop="illegal" label="Illegal">
                    <template #default="scope">
                        <el-switch
                            :active-value="1"
                            :inactive-value="0"
                            @change="updateRow(scope.row)"
                            v-model="scope.row.illegal"
                            class="ml-2"
                            style="--el-switch-on-color: #13ce66; --el-switch-off-color: #ff4949"
                        />
                    </template>
                </el-table-column>
                <el-table-column
                    min-width="150px"
                    prop="comment"
                    label="comment"
                >
                    <template #default="scope">
                        <div @click="switchEditing(scope.row)" v-if="!scope.row.isEditing">
                            <span>{{ scope.row.comment ?? '---' }}</span>
                        </div>
                        <el-input
                            v-else
                            @change="updateRow(scope.row)"
                            v-model="scope.row.comment"
                            type="textarea"
                            :rows="10"
                        ></el-input>
                    </template>
                </el-table-column>
                <el-table-column min-width="230px" prop="last_check_time" label="last_check_time">
                    <template #default="scope">
                        <el-date-picker
                            v-model="scope.row.last_check_time"
                            type="datetime"
                            value-format="YYYY-MM-DD HH:mm:ss"
                            @change="updateRow(scope.row)"
                            placeholder="Select date and time"
                        >
                        </el-date-picker>
                    </template>
                </el-table-column>
                <el-table-column min-width="230px" prop="delivery_time" label="delivery_time">
                    <template #default="scope">
                        <el-date-picker
                            v-model="scope.row.delivery_time"
                            type="datetime"
                            value-format="YYYY-MM-DD HH:mm:ss"
                            @change="updateRow(scope.row)"
                            placeholder="Select date and time"
                        >
                        </el-date-picker>
                    </template>
                </el-table-column>
                <el-table-column prop="last_seen"
                                 label="Last Seen"
                                 min-width="170px"
                ></el-table-column>
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
            <el-link href="https://github.com/job-crawler-tdj/js-script-json-fetcher" target="_blank">
                Js for various playfrom links
            </el-link>
        </div>
        <div class="py-12" v-loading="importLoading">
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
import {toast} from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    mounted() {
        this.get();
    },
    data() {
        return {
            jobs: [],
            importJson: '',
            perPage: 10,
            currentPage: 1,
            total: 0,
            importLoading: false,
            condition: {
                lastCheckTime: null,
                starred: null,
                orderDirection: 'desc',
                orderBy: 'id',
                ifDelivered: null,
            },
        }
    },
    methods: {
        get() {
            fetch('/jobs/list?perPage=' + this.perPage + '&page=' + this.currentPage + '&condition=' + JSON.stringify(this.condition), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
                .then(response => response.json())
                .then(data => {
                    this.jobs = data.data;
                    this.total = data.total;
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        },
        handleCurrentChange(val) {
            this.currentPage = val;
            this.get();
        },
        handleSizeChange(sval) {
            this.perPage = sval;
            this.currentPage = 1;
            this.get();
        },
        switchEditing(row) {
            row.isEditing = !row.isEditing;
        },
        updateRow(row, changeColumn = null) {
            row.isEditing = false;
            if (changeColumn === 'rating') {
                row.last_check_time = new Date().toISOString().slice(0, 19).replace('T', ' ');
            }
            fetch('/jobs/' + row.id, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    lastCheckTime: row.last_check_time,
                    rating: row.rating,
                    deliveryTime: row.delivery_time,
                    comment: row.comment,
                    illegal: row.illegal,
                    minMonthlySalary: row.min_monthly_salary,
                    maxMonthlySalary: row.max_monthly_salary,
                    minAnnualSalary: row.min_annual_salary,
                    maxAnnualSalary: row.max_annual_salary,
                    starred: row.starred,
                    closed: row.closed,
                    jobName: row.job_name,
                    url: row.url,
                })
            })
                .then(response => response.json())
                .then(data => {
                    toast("Update success !", {
                        autoClose: 1000,
                    });
                    console.log('Success:', data);
                    this.get();
                })
                .catch((error) => {
                    toast.error("Update failed !", {
                        autoClose: 1000,
                    });

                    console.error('Error:', error);
                });
        },
        importJobs() {
            this.importLoading = true;
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
                    toast("Data import success !", {
                        autoClose: 1000,
                    });

                    console.log('Success:', data);
                    this.importLoading = false;
                    this.get();
                })
                .catch((error) => {
                    console.error('Error:', error);
                    toast.error("Import failed !", {
                        autoClose: 1000,
                    });

                    this.importLoading = false;
                });
        }
    }
}
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";

</script>
