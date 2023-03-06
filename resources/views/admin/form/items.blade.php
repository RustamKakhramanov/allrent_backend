<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">
    <div class="flex flex-column mt-2 category-items" id="category-items">
        <div class="category-items-list">
            @if ($value)
                @foreach ($value as $key =>$item)
          
                <div class="category_item form-group" >
                        <div class="input-group">
                            <input type="text" name="{{ $name }}[{{ $key }}][name]" class="form-control"
                                value="{{ $item['name'] }}">
                            <input type="hidden" name="{{ $name }}[{{ $key }}][id]" class="form-control"
                                value="{{ $item['id'] }}">
                            <a class="input-group-addon remove-category-item">
                                <i class="fa fa-window-close fa-fw"></i>
                        </div>
                    </div>
                @endforeach()
            @endif
        </div>

        <a class="btn btn-primary c-btn" onclick="addItem(this)">Добавить</button>
    </div>
</div>
</div>
<style>
    .category-items {
        padding: 3px;
        border-radius: 4px;
        border: 1px solid #d2d6de;

    }

    .category_item+.category_item {
        margin-top: 10px
    }

    .c-btn {
        margin-top: 10px
    }

    .remove-category-item {
        cursor: pointer;

    }
</style>
