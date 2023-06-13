<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
    <thead>
        <tr>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7; text-align: center;">Kolektabilitas</th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7; text-align: center;">Nilai </th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7; text-align: center;">Jumlah MB</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Lancar</td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_lancar">{{ $pumk_bulan->kolektabilitas_lancar }}</span></td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_lancar_jumlah_mb">{{ $pumk_bulan->kolektabilitas_lancar_jumlah_mb }}</span></td>
        </tr>
        <tr>
            <td>Kurang Lancar</td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_kurang_lancar">{{ $pumk_bulan->kolektabilitas_kurang_lancar }}</span></td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_kurang_lancar_jumlah_mb">{{ $pumk_bulan->kolektabilitas_kurang_lancar_jumlah_mb }}</span>
            </td>
        </tr>
        <tr>
            <td>Diragukan</td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_diragukan">{{ $pumk_bulan->kolektabilitas_diragukan }}</span></td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_diragukan_jumlah_mb">{{ $pumk_bulan->kolektabilitas_diragukan_jumlah_mb }}</span>
            </td>
        </tr>
        <tr>
            <td>Macet</td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_macet">{{ $pumk_bulan->kolektabilitas_macet }}</span></td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_macet_jumlah_mb">{{ $pumk_bulan->kolektabilitas_macet_jumlah_mb }}</span></td>
        </tr>
        <tr>
            <td>Pinjaman Bermasalah</td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_pinjaman_bermasalah">{{ $pumk_bulan->kolektabilitas_pinjaman_bermasalah }}</span>
            </td>
            <td style="text-align: right;"><span
                    id="kolektabilitas_pinjaman_bermasalah_jumlah_mb">{{ $pumk_bulan->kolektabilitas_pinjaman_bermasalah_jumlah_mb }}</span>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <hr style="border-top: 1px solid #000000;margin-top: 5px; margin-bottom: 0px;">
            </td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td style="text-align: right;"><strong id="total_nilai">
                    {{ $pumk_bulan->kolektabilitas_lancar +
                        $pumk_bulan->kolektabilitas_kurang_lancar +
                        $pumk_bulan->kolektabilitas_diragukan +
                        $pumk_bulan->kolektabilitas_macet +
                        $pumk_bulan->kolektabilitas_pinjaman_bermasalah }}
                </strong></td>
            <td style="text-align: right;"><strong id="total_jumlah_mb">
                    {{ $pumk_bulan->kolektabilitas_lancar_jumlah_mb +
                        $pumk_bulan->kolektabilitas_kurang_lancar_jumlah_mb +
                        $pumk_bulan->kolektabilitas_diragukan_jumlah_mb +
                        $pumk_bulan->kolektabilitas_macet_jumlah_mb +
                        $pumk_bulan->kolektabilitas_pinjaman_bermasalah_jumlah_mb }}
                </strong></td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    var title = "{{ $pagetitle }}";

    $(document).ready(function() {
        $('.modal-title').html(title);
        $('.form-select2').select2();
    });

    document.addEventListener("DOMContentLoaded", function() {
        console.log('halo')
        var elements = document.getElementsByClassName("format-currency");
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            element.textContent = formatCurrency2(element.textContent);
        }
    });

    function formatCurrency2(element) {

        let value = element.replace(/[^\d-]/g, ""); // Remove all non-numeric characters except for hyphen "-"
        const isNegative = value.startsWith("-");
        value = value.replace("-", ""); // Remove hyphen if it exists
        const formatter = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        let formattedValue = formatter.format(value);
        formattedValue = formattedValue.replace(/,/g, ".");
        if (isNegative) {
            formattedValue = "( " + formattedValue + " )";
        }
        element = formattedValue;
        return element

    }
</script>
