<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    let monthlySalesChart, salesByCategoryChart, salesDataChart;

    document.addEventListener("DOMContentLoaded", function() {
        initializeCharts();
        fetchShopData();

        document.getElementById("shop-select").addEventListener("change", fetchShopData);
        document.getElementById("duration-select").addEventListener("change", fetchShopData);
    });

    function initializeCharts() {
        monthlySalesChart = initializeChart({
            selector: "#monthly-sales-chart",
            type: 'bar',
            xaxisTitle: 'Months',
            yaxisTitle: 'Sales Amount (৳)',
            categories: {!! json_encode($monthly_sales->pluck('month')->toArray()) !!},
            data: {!! json_encode($monthly_sales->pluck('total_sales')->toArray()) !!}
        });

        salesByCategoryChart = initializeDonutChart({
            selector: "#sales-by-category-chart",
            categories: {!! json_encode($total_sales_by_category->pluck('category')->toArray()) !!},
            data: {!! json_encode(
                $total_sales_by_category->pluck('percentage')->map(function ($percentage) {
                        return (float) $percentage;
                    })->toArray(),
            ) !!}
        });

        salesDataChart = initializeChart({
            selector: "#sales-data-chart",
            type: 'line',
            xaxisTitle: '{{ ucfirst($duration) }}',
            yaxisTitle: 'Sales Amount (৳)',
            categories: {!! json_encode(array_column($sales_data, 'key')) !!},
            data: {!! json_encode(array_column($sales_data, 'value')) !!}
        });
    }

    function initializeChart({
        selector,
        type,
        xaxisTitle,
        yaxisTitle,
        categories,
        data
    }) {
        const options = {
            series: [{
                name: 'Sales',
                data: data
            }],
            chart: {
                type: type,
                height: 400,
                toolbar: {
                    show: false
                }
            },
            xaxis: {
                categories: categories,
                title: {
                    text: xaxisTitle,
                    style: {
                        fontSize: '14px',
                        fontWeight: '600'
                    }
                },
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: yaxisTitle,
                    style: {
                        fontSize: '14px',
                        fontWeight: '600'
                    }
                },
                labels: {
                    formatter: val => "৳" + val.toFixed(2),
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 5
            },
            tooltip: {
                y: {
                    formatter: val => "৳" + val.toFixed(2)
                }
            }
        };

        const chart = new ApexCharts(document.querySelector(selector), options);
        chart.render();
        return chart;
    }

    function initializeDonutChart({
        selector,
        categories,
        data
    }) {
        const options = {
            series: data,
            chart: {
                type: 'donut',
                width: 380,
                height: 400
            },
            labels: categories,
            colors: ['#7C3AED','#EC4899', '#6B7280', '#4F46E5', '#F97316', '#10B981', '#EF4444', '#3B82F6', '#F59E0B', '#6366F1'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 320,
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230
            },
            tooltip: {
                y: {
                    formatter: val => val + "%"
                }
            },
            fill: {
                type: 'gradient'
            },
            dataLabels: {
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold'
                }
            }
        };

        const chart = new ApexCharts(document.querySelector(selector), options);
        chart.render();
        return chart;
    }

    async function fetchShopData() {
        const shopId = document.getElementById("shop-select").value;
        const duration = document.getElementById("duration-select").value;

        try {
            const response = await fetch(`/shop-data?shop_id=${shopId}&duration=${duration}`);
            const data = await response.json();

            updateDOMData(data);
            updateCharts(data, duration);
        } catch (error) {
            console.error('Error fetching shop data:', error);
        }
    }

    function updateDOMData(data) {
        $('#total-orders').text(data.total_order);
        $('#total-sold').text(data.total_sold);
        $('#total-sales').text(data.total_sales);
        $('#total-discount').text(data.total_discount);
        $('#total-customers').text(data.total_customers);

        const topCustomersTable = data.top_customer.map((customer, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${customer.customer.name}</td>
            <td>${customer.customer.phone}</td>
            <td>${customer.total_orders}</td>
        </tr>
    `).join('');

        $('#top-customers').html(topCustomersTable);

        const topSellingTable = data.top_selling_products.map((product, index) => `
        <tr>
            <td>${index + 1}</td>
            <td style="display: flex; align-items: center;">
                <div>
                    <span style="font-weight: bold; display: block;">${product.product.name}</span>
                    <p class="mb-0 text-secondary" style="margin: 0; font-size: 0.875rem;">
                        <small><strong>Slug:</strong> ${product.product.slug}</small>
                    </p>
                </div>
            </td>
            <td>${product.total_sold}</td>
        </tr>
    `).join('');
        $('#top-products').html(topSellingTable);
    }

    function updateCharts(data, duration) {
        updateChart(salesDataChart, {
            categories: data.sales_data.map(item => item.key),
            data: data.sales_data.map(item => item.value),
            xaxisTitle: duration.charAt(0).toUpperCase() + duration.slice(1)
        });

        updateChart(monthlySalesChart, {
            categories: data.monthly_sales.map(item => item.month),
            data: data.monthly_sales.map(item => item.total_sales)
        });

        salesByCategoryChart.updateOptions({
            series: data.total_sales_by_category.map(item => parseFloat(item.percentage)),
            labels: data.total_sales_by_category.map(item => item.category)
        });
    }

    function updateChart(chart, {
        categories,
        data,
        xaxisTitle
    }) {
        chart.updateOptions({
            series: [{
                name: 'Sales',
                data: data
            }],
            xaxis: {
                categories: categories,
                title: {
                    text: xaxisTitle
                }
            }
        });
    }
</script>