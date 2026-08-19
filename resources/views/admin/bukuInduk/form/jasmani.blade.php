<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Tahun</th>
            <th>Berat Badan (kg)</th>
            <th>Tinggi Badan (cm)</th>
            <th>Penyakit</th>
            <th>Kelainan Jasmani</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jasmani as $index => $jas)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <input type="number" name="jasmani[{{ $index }}][tahun]" class="form-control"
                        value="{{ old("jasmani.$index.tahun", $jas->tahun ?? '') }}">
                </td>
                <td>
                    <input type="number" name="jasmani[{{ $index }}][berat_badan]" class="form-control"
                        value="{{ old("jasmani.$index.berat_badan", $jas->berat_badan ?? '') }}">
                </td>
                <td>
                    <input type="number" name="jasmani[{{ $index }}][tinggi_badan]" class="form-control"
                        value="{{ old("jasmani.$index.tinggi_badan", $jas->tinggi_badan ?? '') }}">
                </td>
                <td>
                    <input type="text" name="jasmani[{{ $index }}][penyakit]" class="form-control"
                        value="{{ old("jasmani.$index.penyakit", $jas->penyakit ?? '') }}">
                </td>
                <td>
                    <input type="text" name="jasmani[{{ $index }}][kelainan_jasmani]" class="form-control"
                        value="{{ old("jasmani.$index.kelainan_jasmani", $jas->kelainan_jasmani ?? '') }}">
                </td>
            </tr>
        @endforeach

        {{-- Tambah 1 baris kosong untuk input baru --}}
        <tr>
            <td>{{ count($jasmani) + 1 }}</td>
            <td><input type="number" name="jasmani[{{ count($jasmani) }}][tahun]" class="form-control"></td>
            <td><input type="number" name="jasmani[{{ count($jasmani) }}][berat_badan]" class="form-control"></td>
            <td><input type="number" name="jasmani[{{ count($jasmani) }}][tinggi_badan]" class="form-control"></td>
            <td><input type="text" name="jasmani[{{ count($jasmani) }}][penyakit]" class="form-control"></td>
            <td><input type="text" name="jasmani[{{ count($jasmani) }}][kelainan_jasmani]" class="form-control">
            </td>
        </tr>
    </tbody>

</table>
