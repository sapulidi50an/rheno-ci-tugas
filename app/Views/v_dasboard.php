<script>
fetch('http://localhost/api', {
    headers: {
        'Key': '45HdbCNX4242c4b481425130xFedFYOr'
    }
})
.then(res => res.json())
.then(data => {
    if(data.status.code === 200) {
        let html = '';
        data.results.forEach((item, i) => {
            html += `<tr>
                <td>${i+1}</td>
                <td>${item.username ?? '-'}</td>
                <td>${item.alamat ?? '-'}</td>
                <td>${item.total_harga ? new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR'}).format(item.total_harga) : '-'}</td>
                <td>${item.ongkir ? new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR'}).format(item.ongkir) : '-'}</td>
                <td>${item.status ?? '-'}</td>
                <td>${item.created_at ?? '-'}</td>
            </tr>`;
        });
        document.querySelector('tbody').innerHTML = html;
    }
});
</script>