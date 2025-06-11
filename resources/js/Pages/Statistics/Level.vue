<template>
    <div>
        <h2 class="title-xl">레벨 순위</h2>
        <div class="flex flex-col lg:flex-row gap-3 md:gap-8 justify-between">
            <!-- 차트 컨테이너  -->
            <div class="relative h-96 lg:w-2/5 flex-shrink-0">
                <Doughnut :data="doughnutData" :options="options" :plugins="[doughnutCenterText]" />
            </div>
            <!-- 스탯 그리드 -->
            <div class="grid grid-cols-3 gap-3 lg:w-3/5">
                <div v-for="stat in levelStats" :key="stat.title" :class="[
                    'p-4 text-gray-700 dark:text-gray-300 dark:bg-gray-800 border rounded-lg shadow-sm',
                    stat.isTotal ? 'bg-sky-600 text-white' : '',
                    stat.colSpan
                ]">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-sm">{{ stat.title }}</h3>
                            <div :class="['md:w-3 md:h-3 w-2.5 h-2.5 rounded-full', stat.color]"></div>
                        </div>
                        <p class="text-2xl font-bold mb-1">{{ stat.percentage }}%</p>
                        <p class="text-sm">{{ stat.count }}/20</p>
                    </div>
                </div>
                <div class="p-4 bg-sky-600 text-white dark:text-gray-300 dark:bg-gray-800 border rounded-lg shadow-sm">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-sm">{{ totalStat.title }}</h3>
                        </div>
                        <p class="text-2xl font-bold mb-1">{{ totalStat.percentage }}%</p>
                        <p class="text-sm">{{ totalStat.count }}/20</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import ChartDataLabels from 'chartjs-plugin-datalabels'
import { LEVEL_COLOR_MAP } from '@/Utils/Constant'
import { LEVEL_TAILWIND_COLOR_MAP } from '@/Utils/Constant'

const { levelRatioData, selectedLog } = defineProps({
    levelRatioData: Object,
    selectedLog: Object,
})

ChartJS.register(ArcElement, Tooltip, Legend, ChartDataLabels)

// 도넛 차트 중앙 텍스트
const doughnutCenterText = {
    id: 'doughnutCenterText',
    beforeDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data } = chart;
        const { width, height } = chart.chartArea;

        ctx.save();
        const text = selectedLog.emoji + ' ' + selectedLog.title;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        const x = chart.getDatasetMeta(0).data[0].x;
        const y = chart.getDatasetMeta(0).data[0].y;
        ctx.fillText(text, x, y);

        ctx.restore();
    }
}

// 도넛 차트에 쓰일 데이터
const filteredByCountLevelData = computed(() => 
    levelRatioData['dataList'].filter(item => item.count > 0)
)
const labels = computed(() => filteredByCountLevelData.value.map(item => {
    if (item.level > 0) return 'Lv.' + item.level
    else if (item.level === 0) return 'SKIP'
}))
const doughnutCountData = computed(() => filteredByCountLevelData.value.map(item => item.count))
const backgroundColors = computed(() =>
    filteredByCountLevelData.value.map(item =>
        item.level && LEVEL_COLOR_MAP[item.level] ? LEVEL_COLOR_MAP[item.level] : '#e1e1e1'
    )
)
const doughnutData = computed(() => ({
    labels: labels.value,
    datasets: [
        {
            backgroundColor: backgroundColors.value,
            data: doughnutCountData.value,
            cutout: '40%',
            borderWidth: 0,
        }
    ]
}))

// 그리드에 쓰일 데이터
const levelStats = computed(() => {
    return levelRatioData['dataList']
        .map(item => {
            return {
                level: item.level,
                title: item.level > 0 ? 'Level ' + item.level : 'SKIP',
                percentage: item.percentage,
                count: item.count,
                color: item.level > 0 ? LEVEL_TAILWIND_COLOR_MAP[item.level] : 'bg-gray-500',
                colSpan: item.level > 0 ? 'col-span-1' : 'col-span-2',
            }
        })
        // 레벨 0 은 가장 마지막에 오게 처리
        .sort((a, b) => {
            if (a.level === 0) return 1
            if (b.level === 0) return -1
            return a.level - b.level;
        })
})

const totalStat = computed(() => {
    return {
        title: 'TOTAL',
        percentage: levelRatioData['checkedTotalCount'] ? Math.round(levelRatioData['checkedTotalCount'] / 20 * 100) : 0, // 20일 중 체크한 비율
        count: levelRatioData['checkedTotalCount'] ?? 0,
        isTotal: true,
        colSpan: 'col-span-1'
    }
})

const options = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            enabled: false, // 툴팁 제거
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
</script>