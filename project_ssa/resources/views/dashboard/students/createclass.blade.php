<!-- Add Students Modal -->

<div class="modal fade" id="exampleModaladd" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">Add Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body with form -->
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Year</label>
                            <select id="year_id" name="year_id" class="form-select">
                                @foreach ($datas as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Semester</label>
                            <select id="semester_id" name="semester_id" class="form-select">
                                @foreach ($semesters as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Shift</label>
                            <select id="shift_id" name="shift_id" class="form-select">
                                @foreach ($shifts as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Room</label>
                            {{-- <input type="number" id="room_id" class="form-control"> --}}
                            <select id="room_id" class="form-select">
                                <option value="" selected disabled hidden>សូមជ្រើសរើស</option>
                                @foreach ($rooms as $item)
                                    <option value="{{ $item['name'] }}">
                                        បន្ទប់សិក្សា {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-12">
                                <label>Total Students</label>
                                <input type="number" id="total_students" name="total_students" class="form-control"
                                    min="1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="addclassStudent()">Save Students</button>
                </div>
            </form>

        </div>
    </div>
</div>
