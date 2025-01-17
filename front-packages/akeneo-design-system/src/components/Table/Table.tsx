import React, {ReactNode, useMemo} from 'react';
import styled from 'styled-components';
import {TableCell} from './TableCell/TableCell';
import {TableHeader} from './TableHeader/TableHeader';
import {TableHeaderCell} from './TableHeaderCell/TableHeaderCell';
import {TableActionCell} from './TableActionCell/TableActionCell';
import {TableRow} from './TableRow/TableRow';
import {TableContext} from './TableContext';
import {TableBody} from './TableBody/TableBody';
import {Override} from '../../shared';

const TableContainer = styled.table`
  border-collapse: collapse;
  width: 100%;
`;

type TableProps = Override<
  React.HTMLAttributes<HTMLTableElement>,
  {
    /**
     * Define if rows can be selected.
     */
    isSelectable?: boolean;

    /**
     * Define if the checkbox should be always displayed or displayed on hover.
     * This props should be true when one element is checked.
     */
    displayCheckbox?: boolean;

    /**
     * The content of the table.
     */
    children?: ReactNode;
  } & (
    | {
        /**
         * Define if rows can be reordered with drag and drop.
         */
        isDragAndDroppable?: false;

        onReorder?: undefined;
      }
    | {
        /**
         * Define if rows can be reordered with drag and drop.
         */
        isDragAndDroppable: boolean;

        /**
         * Called when an element has been dragged and dropped on the table.
         */
        onReorder: (updatedIndices: number[]) => void;
      }
  )
>;

/**
 * Tables allow users to analyze and manipulate data.
 */
const Table = ({
  isSelectable = false,
  displayCheckbox = false,
  isDragAndDroppable = false,
  onReorder = undefined,
  children,
  ...rest
}: TableProps) => {
  const providerValue = useMemo(() => ({isSelectable, displayCheckbox, isDragAndDroppable, onReorder}), [
    isSelectable,
    displayCheckbox,
    isDragAndDroppable,
    onReorder,
  ]);

  return (
    <TableContext.Provider value={providerValue}>
      <TableContainer {...rest}>{children}</TableContainer>
    </TableContext.Provider>
  );
};

TableHeader.displayName = 'Table.Header';
TableHeaderCell.displayName = 'Table.HeaderCell';
TableBody.displayName = 'Table.Body';
TableRow.displayName = 'Table.Row';
TableCell.displayName = 'Table.Cell';
TableActionCell.displayName = 'Table.ActionCell';

Table.Header = TableHeader;
Table.HeaderCell = TableHeaderCell;
Table.Body = TableBody;
Table.Row = TableRow;
Table.Cell = TableCell;
Table.ActionCell = TableActionCell;

export {Table};
