<script setup lang="ts">
import { computed } from 'vue';

interface ChartData {
    labels: string[];
    datasets: Array<{
        label: string;
        data: number[];
        backgroundColor?: string[];
        borderColor?: string[];
        borderWidth?: number;
    }>;
}

interface Props {
    data: ChartData;
    type?: 'bar' | 'line' | 'pie' | 'doughnut';
    options?: {
        responsive?: boolean;
        maintainAspectRatio?: boolean;
        plugins?: {
            legend?: {
                display?: boolean;
                position?: 'top' | 'bottom' | 'left' | 'right';
            };
            title?: {
                display?: boolean;
                text?: string;
            };
        };
        scales?: {
            x?: {
                display?: boolean;
                title?: {
                    display?: boolean;
                    text?: string;
                };
            };
            y?: {
                display?: boolean;
                title?: {
                    display?: boolean;
                    text?: string;
                };
                beginAtZero?: boolean;
            };
        };
    };
    height?: number;
    width?: number;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'bar',
    height: 300,
    width: 400,
    options: () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
        },
        scales: {
            x: {
                display: true,
            },
            y: {
                display: true,
                beginAtZero: true,
            },
        },
    }),
});

const chartId = computed(() => `chart-${Math.random().toString(36).substr(2, 9)}`);

const defaultColors = [
    '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
    '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16',
];

const processedData = computed(() => {
    const processed = { ...props.data };
    
    // Add default colors if not provided
    if (processed.datasets[0] && !processed.datasets[0].backgroundColor) {
        processed.datasets[0].backgroundColor = defaultColors;
    }
    
    return processed;
});

const renderChart = () => {
    // This is a simplified chart rendering
    // In a real implementation, you would use a charting library like Chart.js
    const canvas = document.getElementById(chartId.value) as HTMLCanvasElement;
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Simple bar chart implementation
    if (props.type === 'bar') {
        const data = processedData.value.datasets[0].data;
        const labels = processedData.value.labels;
        const maxValue = Math.max(...data);
        const barWidth = (canvas.width - 60) / data.length;
        const chartHeight = canvas.height - 60;

        // Draw axes
        ctx.strokeStyle = '#e5e7eb';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(40, 20);
        ctx.lineTo(40, chartHeight + 20);
        ctx.lineTo(canvas.width - 20, chartHeight + 20);
        ctx.stroke();

        // Draw bars
        data.forEach((value, index) => {
            const barHeight = (value / maxValue) * chartHeight;
            const x = 50 + index * barWidth;
            const y = chartHeight + 20 - barHeight;

            // Bar
            ctx.fillStyle = defaultColors[index % defaultColors.length];
            ctx.fillRect(x, y, barWidth - 10, barHeight);

            // Value label
            ctx.fillStyle = '#374151';
            ctx.font = '12px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(value.toString(), x + (barWidth - 10) / 2, y - 5);

            // X-axis label
            ctx.save();
            ctx.translate(x + (barWidth - 10) / 2, chartHeight + 35);
            ctx.rotate(-Math.PI / 6);
            ctx.textAlign = 'right';
            ctx.fillText(labels[index], 0, 0);
            ctx.restore();
        });
    }
};

// Auto-render when component is mounted
import { onMounted, watch } from 'vue';

onMounted(() => {
    renderChart();
});

watch(() => props.data, () => {
    renderChart();
}, { deep: true });
</script>

<template>
    <div class="chart-container" :style="{ width: width + 'px', height: height + 'px' }">
        <canvas 
            :id="chartId" 
            :width="width" 
            :height="height"
            class="w-full h-full"
        ></canvas>
    </div>
</template>

<style scoped>
.chart-container {
    position: relative;
    margin: 0 auto;
}
</style>