<a
    href="{{ route('employees.edit', ['employee' => $employeeId]) }}"
    class="btn btn-sm btn-light">
    <span class="d-flex align-items-center">
        <i class="far fa-edit mr-1"></i>
    </span>
</a>
<form
    id="deleteForm-{{ $employeeId }}" action="{{ route('employees.destroy', ['employee' => $employeeId]) }}"
    method="POST"
    style="display: inline;">
    @csrf
    @method("DELETE")
    <button type="submit" class="btn btn-sm btn-light" onclick="return confirm('Вы уверены?')">
        <span class="d-flex align-items-center">
            <i class="far fa-trash-alt mr-1"></i>
        </span>
    </button>
</form>
