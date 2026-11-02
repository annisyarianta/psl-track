<table>
    <thead>
        @isset($judulLaporan)
            <tr>
                <th colspan="{{ count($daftarJudul) }}">{{ $judulLaporan }}</th>
            </tr>
            <tr>
                <th colspan="{{ count($daftarJudul) }}">{{ $keteranganUnit }}</th>
            </tr>
            <tr>
                <th colspan="{{ count($daftarJudul) }}"></th>
            </tr>
        @endisset
        <tr>
            @foreach ($daftarJudul as $judulKolom)
                <th>{{ $judulKolom }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($daftarBaris as $barisData)
            <tr>
                @foreach ($barisData as $sel)
                    @if ($sel['rowspan'] > 0)
                        <td rowspan="{{ $sel['rowspan'] }}">{{ $sel['teks'] }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
