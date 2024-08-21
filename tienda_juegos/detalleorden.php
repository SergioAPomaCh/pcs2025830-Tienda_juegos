<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page_includes.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class detalleordenPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Detalleorden');
            $this->SetMenuLabel('Detalleorden');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`detalleorden`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id_detalle', true, true, true),
                    new IntegerField('id_orden', true),
                    new IntegerField('id_juego', true),
                    new IntegerField('cantidad', true),
                    new IntegerField('precio_unitario', true),
                    new IntegerField('subtotal', true)
                )
            );
            $this->dataset->AddLookupField('id_orden', 'ordenescompra', new IntegerField('id_orden'), new IntegerField('id_cliente', false, false, false, false, 'id_orden_id_cliente', 'id_orden_id_cliente_ordenescompra'), 'id_orden_id_cliente_ordenescompra');
            $this->dataset->AddLookupField('id_juego', 'juegosfisicos', new IntegerField('id_juego'), new StringField('titulo', false, false, false, false, 'id_juego_titulo', 'id_juego_titulo_juegosfisicos'), 'id_juego_titulo_juegosfisicos');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id_detalle', 'id_detalle', 'Id Detalle'),
                new FilterColumn($this->dataset, 'id_orden', 'id_orden_id_cliente', 'Id Orden'),
                new FilterColumn($this->dataset, 'id_juego', 'id_juego_titulo', 'Id Juego'),
                new FilterColumn($this->dataset, 'cantidad', 'cantidad', 'Cantidad'),
                new FilterColumn($this->dataset, 'precio_unitario', 'precio_unitario', 'Precio Unitario'),
                new FilterColumn($this->dataset, 'subtotal', 'subtotal', 'Subtotal')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id_detalle'])
                ->addColumn($columns['id_orden'])
                ->addColumn($columns['id_juego'])
                ->addColumn($columns['cantidad'])
                ->addColumn($columns['precio_unitario'])
                ->addColumn($columns['subtotal']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('id_orden')
                ->setOptionsFor('id_juego');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_detalle_edit');
            
            $filterBuilder->addColumn(
                $columns['id_detalle'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('id_orden_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_detalleorden_id_orden_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_orden', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_detalleorden_id_orden_search');
            
            $filterBuilder->addColumn(
                $columns['id_orden'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('id_juego_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_detalleorden_id_juego_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_juego', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_detalleorden_id_juego_search');
            
            $text_editor = new TextEdit('id_juego');
            
            $filterBuilder->addColumn(
                $columns['id_juego'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('cantidad_edit');
            
            $filterBuilder->addColumn(
                $columns['cantidad'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('precio_unitario_edit');
            
            $filterBuilder->addColumn(
                $columns['precio_unitario'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('subtotal_edit');
            
            $filterBuilder->addColumn(
                $columns['subtotal'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->deleteOperationIsAllowed()) {
                $operation = new AjaxOperation(OPERATION_DELETE,
                    $this->GetLocalizerCaptions()->GetMessageString('Delete'),
                    $this->GetLocalizerCaptions()->GetMessageString('Delete'), $this->dataset,
                    $this->GetModalGridDeleteHandler(), $grid
                );
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
            }
            
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id_detalle field
            //
            $column = new NumberViewColumn('id_detalle', 'id_detalle', 'Id Detalle', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for id_cliente field
            //
            $column = new NumberViewColumn('id_orden', 'id_orden_id_cliente', 'Id Orden', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for titulo field
            //
            $column = new TextViewColumn('id_juego', 'id_juego_titulo', 'Id Juego', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for precio_unitario field
            //
            $column = new NumberViewColumn('precio_unitario', 'precio_unitario', 'Precio Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for subtotal field
            //
            $column = new NumberViewColumn('subtotal', 'subtotal', 'Subtotal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id_detalle field
            //
            $column = new NumberViewColumn('id_detalle', 'id_detalle', 'Id Detalle', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for id_cliente field
            //
            $column = new NumberViewColumn('id_orden', 'id_orden_id_cliente', 'Id Orden', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for titulo field
            //
            $column = new TextViewColumn('id_juego', 'id_juego_titulo', 'Id Juego', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_unitario field
            //
            $column = new NumberViewColumn('precio_unitario', 'precio_unitario', 'Precio Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for subtotal field
            //
            $column = new NumberViewColumn('subtotal', 'subtotal', 'Subtotal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id_orden field
            //
            $editor = new DynamicCombobox('id_orden_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Orden', 'id_orden', 'id_orden_id_cliente', 'edit_detalleorden_id_orden_search', $editor, $this->dataset, $lookupDataset, 'id_orden', 'id_cliente', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_juego field
            //
            $editor = new DynamicCombobox('id_juego_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Juego', 'id_juego', 'id_juego_titulo', 'edit_detalleorden_id_juego_search', $editor, $this->dataset, $lookupDataset, 'id_juego', 'titulo', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_unitario field
            //
            $editor = new TextEdit('precio_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Unitario', 'precio_unitario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for subtotal field
            //
            $editor = new TextEdit('subtotal_edit');
            $editColumn = new CustomEditColumn('Subtotal', 'subtotal', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for id_orden field
            //
            $editor = new DynamicCombobox('id_orden_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Orden', 'id_orden', 'id_orden_id_cliente', 'multi_edit_detalleorden_id_orden_search', $editor, $this->dataset, $lookupDataset, 'id_orden', 'id_cliente', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_juego field
            //
            $editor = new DynamicCombobox('id_juego_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Juego', 'id_juego', 'id_juego_titulo', 'multi_edit_detalleorden_id_juego_search', $editor, $this->dataset, $lookupDataset, 'id_juego', 'titulo', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for precio_unitario field
            //
            $editor = new TextEdit('precio_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Unitario', 'precio_unitario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for subtotal field
            //
            $editor = new TextEdit('subtotal_edit');
            $editColumn = new CustomEditColumn('Subtotal', 'subtotal', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddToggleEditColumns(Grid $grid)
        {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id_orden field
            //
            $editor = new DynamicCombobox('id_orden_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Orden', 'id_orden', 'id_orden_id_cliente', 'insert_detalleorden_id_orden_search', $editor, $this->dataset, $lookupDataset, 'id_orden', 'id_cliente', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_juego field
            //
            $editor = new DynamicCombobox('id_juego_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Juego', 'id_juego', 'id_juego_titulo', 'insert_detalleorden_id_juego_search', $editor, $this->dataset, $lookupDataset, 'id_juego', 'titulo', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_unitario field
            //
            $editor = new TextEdit('precio_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Unitario', 'precio_unitario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for subtotal field
            //
            $editor = new TextEdit('subtotal_edit');
            $editColumn = new CustomEditColumn('Subtotal', 'subtotal', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id_detalle field
            //
            $column = new NumberViewColumn('id_detalle', 'id_detalle', 'Id Detalle', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for id_cliente field
            //
            $column = new NumberViewColumn('id_orden', 'id_orden_id_cliente', 'Id Orden', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for titulo field
            //
            $column = new TextViewColumn('id_juego', 'id_juego_titulo', 'Id Juego', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddPrintColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_unitario field
            //
            $column = new NumberViewColumn('precio_unitario', 'precio_unitario', 'Precio Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for subtotal field
            //
            $column = new NumberViewColumn('subtotal', 'subtotal', 'Subtotal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_detalle field
            //
            $column = new NumberViewColumn('id_detalle', 'id_detalle', 'Id Detalle', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for id_cliente field
            //
            $column = new NumberViewColumn('id_orden', 'id_orden_id_cliente', 'Id Orden', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for titulo field
            //
            $column = new TextViewColumn('id_juego', 'id_juego_titulo', 'Id Juego', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddExportColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_unitario field
            //
            $column = new NumberViewColumn('precio_unitario', 'precio_unitario', 'Precio Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for subtotal field
            //
            $column = new NumberViewColumn('subtotal', 'subtotal', 'Subtotal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for id_cliente field
            //
            $column = new NumberViewColumn('id_orden', 'id_orden_id_cliente', 'Id Orden', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for titulo field
            //
            $column = new TextViewColumn('id_juego', 'id_juego_titulo', 'Id Juego', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddCompareColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_unitario field
            //
            $column = new NumberViewColumn('precio_unitario', 'precio_unitario', 'Precio Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for subtotal field
            //
            $column = new NumberViewColumn('subtotal', 'subtotal', 'Subtotal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(4);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && true);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddToggleEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setAllowedActions(array('view', 'insert', 'copy', 'edit', 'multi-edit', 'delete', 'multi-delete'));
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setAllowPrintSelectedRecords(true);
            $this->setExportListAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'insert_detalleorden_id_orden_search', 'id_orden', 'id_cliente', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'insert_detalleorden_id_juego_search', 'id_juego', 'titulo', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'filter_builder_detalleorden_id_orden_search', 'id_orden', 'id_cliente', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'filter_builder_detalleorden_id_juego_search', 'id_juego', 'titulo', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'edit_detalleorden_id_orden_search', 'id_orden', 'id_cliente', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'edit_detalleorden_id_juego_search', 'id_juego', 'titulo', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`ordenescompra`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_orden', true, true, true),
                    new IntegerField('id_cliente', true),
                    new DateTimeField('fecha_orden', true),
                    new IntegerField('total', true)
                )
            );
            $lookupDataset->setOrderByField('id_cliente', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'multi_edit_detalleorden_id_orden_search', 'id_orden', 'id_cliente', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`juegosfisicos`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_juego', true, true, true),
                    new StringField('titulo', true),
                    new StringField('plataforma', true),
                    new StringField('genero'),
                    new IntegerField('precio', true),
                    new IntegerField('stock', true),
                    new StringField('descripcion'),
                    new DateField('fecha_lanzamiento'),
                    new StringField('proveedor')
                )
            );
            $lookupDataset->setOrderByField('titulo', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, 'multi_edit_detalleorden_id_juego_search', 'id_juego', 'titulo', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new detalleordenPage("detalleorden", "detalleorden.php", GetCurrentUserPermissionsForPage("detalleorden"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("detalleorden"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
