{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
<x-backpack::menu-item title="Posts" icon="la la-question" :link="backpack_url('project')" />
<x-backpack::menu-item title="Comments" icon="la la-question" :link="backpack_url('comments')" />
<x-backpack::menu-item title="User applications" icon="la la-question" :link="backpack_url('user-application')" />
<x-backpack::menu-item title="Employers" icon="la la-question" :link="backpack_url('employers')" />
<x-backpack::menu-item title="Backups" icon="la la-database" :link="route('admin.backup.index')" />
<x-backpack::menu-item title="Logs" icon="la la-exclamation-triangle" :link="route('admin.logs.index')" />

