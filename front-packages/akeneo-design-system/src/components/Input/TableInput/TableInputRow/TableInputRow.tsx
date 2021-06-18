import styled from "styled-components";
import React, { forwardRef, HTMLAttributes, Ref } from "react";
import { Override } from "../../../../shared";
import { getColor } from "../../../../theme";

const TableInputTr = styled.tr`
  height: 40px;
  & > * {
    border: 1px solid ${getColor('grey', 60)};
    border-left-width: 0;
    border-top-width: 0;
  }
  & > *:first-child {
    border-left-width: 1px;
    position: sticky;
    left: 0;
    z-index: 1;
  }
`;

type TableInputRowProps = Override<
  HTMLAttributes<HTMLTableRowElement>,
  {}
>;

const TableInputRow = forwardRef<HTMLTableRowElement, TableInputRowProps>(
  ({children, ...rest}: TableInputRowProps, forwardedRef: Ref<HTMLTableRowElement>) => {
  return <TableInputTr ref={forwardedRef} {...rest}>
    {children}
  </TableInputTr>;
});

TableInputRow.displayName = 'TableInput.Row';

export {TableInputRow};