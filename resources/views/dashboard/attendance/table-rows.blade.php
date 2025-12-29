{{-- @foreach ($attendanceData as $item)
<tr class="attendance-row">
    <td>{{ $loop->iteration }}</td>

    <td class="student-id">
        {{ $item['student_id'] }}
    </td>

    <td class="student-name">
        {{ $item['student_name'] }}
    </td>
    <td class="student-gender" id="gender">
        {{ $item['gender'] === 'M' ? 'ប្រុស' : 'ស្រី' }}
    </td>


    <td>
        <select class="form-select status">
            <option value="present">វត្តមាន</option>
            <option value="absent">អវត្តមាន</option>
            <option value="permission">ច្បាប់</option>
        </select>
    </td>
</tr>


@endforeach

<tr>
    <td colspan="5">
  <div class="row text-center">
    <!-- Column 1: Teacher Name -->
    <div class="col">
      <label for="teacherName" class="fw-bold d-block mb-2">ឈ្មោះគ្រូបង្រៀន</label>
      <input type="text" id="teacherName" class="form-control mx-auto" placeholder="បញ្ចូលឈ្មោះគ្រូ">
    </div>

    <!-- Column 2: Subject Name -->
    <div class="col">
      <label for="subjectName" class="fw-bold d-block mb-2">ឈ្មោះមុខវិជ្ជា</label>
      <input type="text" id="subjectName" class="form-control mx-auto" placeholder="បញ្ចូលឈ្មោះមុខវិជ្ជា">
    </div>
  </div>
</td>


</tr> --}}
