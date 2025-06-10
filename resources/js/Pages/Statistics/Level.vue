<template>
    <div>
        <h2 class="title-xl">레벨 순위</h2>
        <div class="flex flex-col lg:flex-row gap-3 md:gap-8 justify-between">
            <!-- 차트 컨테이너  -->
            <div class="relative h-96 lg:w-2/5 flex-shrink-0">
                <Doughnut :data="data" :options="options" :plugins="[doughnutCenterText]" />
            </div>
            <!-- 스탯 그리드 -->
            <div class="grid grid-cols-3 gap-3 lg:w-3/5">
                <div v-for="stat in stats" :key="stat.title" :class="[
                    'p-4 text-gray-700 dark:text-gray-300 dark:bg-gray-800 border rounded-lg shadow-sm',
                    stat.isTotal ? 'bg-sky-600 text-white' : '',
                    stat.colSpan
                ]">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-sm">{{ stat.title }}</h3>
                            <div :class="['w-3 h-3 rounded-full', stat.color]"></div>
                        </div>
                        <p class="text-2xl font-bold mb-1">{{ stat.percentage }}</p>
                        <p class="text-sm">{{ stat.ratio }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import ChartDataLabels from 'chartjs-plugin-datalabels'

ChartJS.register(ArcElement, Tooltip, Legend, ChartDataLabels)

const doughnutCenterText = {
    id: 'doughnutCenterText',
    beforeDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data } = chart;
        const { width, height } = chart.chartArea;

        ctx.save();
        const text = 'Total';
        ctx.font = 'bold 30px';
        ctx.fillStyle = '#333';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        const x = chart.getDatasetMeta(0).data[0].x;
        const y = chart.getDatasetMeta(0).data[0].y;
        ctx.fillText(text, x, y);

        ctx.restore();
    }
}

const data = ref({
    labels: ['VueJs', 'EmberJs', 'ReactJs', 'AngularJs'],
    datasets: [
        {
            backgroundColor: ['#0ea5e9', '#38bdf8', '#7dd3fc', '#0284c7'],
            data: [40, 20, 80, 10],
            cutout: '50%',
        }
    ]
})

const options = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            enabled: false, // 툴팁 유지
        },
        datalabels: {
            color: '#fff',
            font: {
                weight: 'bold',
                size: 12,
            },
            formatter: (value, context) => {
                const label = context.chart.data.labels[context.dataIndex];
                return label + '\n' + value + '일';
            },
            textAlign: 'center',
        }
    }
})

const stats = ref([
    {
        title: 'Level 1', percentage: '6%', ratio: '1/18',
        color: 'bg-sky-200',
        colSpan: 'col-span-1'
    },
    {
        title: 'Level 2', percentage: '0%', ratio: '0/18',
        color: 'bg-sky-300',
        colSpan: 'col-span-1'
    },
    {
        title: 'Level 3', percentage: '0%', ratio: '0/18',
        color: 'bg-sky-400',
        colSpan: 'col-span-1'
    },
    {
        title: 'SKIP', percentage: '94%', ratio: '17/18',
        color: 'bg-gray-500',
        colSpan: 'col-span-2' // 모바일에서 2칸을 차지해서 두 번째 줄로 이동
    },
    {
        title: 'TOTAL', percentage: '6%', ratio: '17/18',
        isTotal: true,
        colSpan: 'col-span-1'
    },
])

</script>