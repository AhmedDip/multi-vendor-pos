<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Menu Name', 'name')}}
        <x-required/>
        {{html()->text('name')->class('form-control form-control-sm'. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter menu name'))}}
        <x-validation-error :errors="$errors->first('name')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Menu icon', 'icon')}}
        {{html()->text('icon')->class('form-control form-control-sm'. ($errors->has('icon') ? 'is-invalid' : ''))->placeholder(__('<i class="mdi mdi-apps"></i>'))}}
        <x-validation-error :errors="$errors->first('icon')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Menu sort order', 'sort_order')}}
        <x-required/>
        {{html()->text('sort_order')->class('form-control form-control-sm'. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter menu sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Parent Menu', 'menu_id')}}
        {{html()->select('menu_id',$menus)->class('form-select select2 '. ($errors->has('menu_id') ? 'is-invalid' : ''))->placeholder('Select Parent Menu')}}
        <x-validation-error :errors="$errors->first('menu_id')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Menu action route', 'route')}}
        {{html()->select('route',$routes)->class('form-select select2 '. ($errors->has('route') ? 'is-invalid' : ''))->placeholder(__('Select Menu Action Route'))}}
        <x-validation-error :errors="$errors->first('route')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Menu query string', 'query_string')}}
        {{html()->text('query_string')->class('form-control form-control-sm'. ($errors->has('query_string') ? 'is-invalid' : ''))->placeholder(__('Enter query string'))}}
        <x-validation-error :errors="$errors->first('query_string')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required/>
        {{html()->select('status',\App\Models\Menu::STATUS_LIST)->class('form-select form-select-sm'. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Menu Status'))}}
        <x-validation-error :errors="$errors->first('status')"/>
    </div>
</div>


