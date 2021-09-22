import React, {FC} from 'react';
import styled from 'styled-components';
import {getColor, getFontSize, Button} from 'akeneo-design-system';
import {useTranslate} from '../../../shared/translate';
import {ConnectedApp} from '../../../model/connected-app';

const Grid = styled.section`
    margin: 20px 0;
    display: grid;
    grid-template-columns: repeat(2, minmax(300px, 1fr));
    gap: 20px;
`;

const CardContainer = styled.div`
    padding: 20px;
    border: 1px ${getColor('grey', 40)} solid;
    display: grid;
    gap: 0 20px;
    grid-template-columns: 100px 1fr 1px; /* 1px column only for ellipsis working */
    grid-template-rows: 75px 25px;
    grid-template-areas:
        'logo text text'
        'logo actions actions';
`;

const LogoContainer = styled.div`
    width: 100px;
    height: 100px;
    grid-area: logo;
    border: 1px ${getColor('grey', 40)} solid;
    display: flex;
`;

const Logo = styled.img`
    margin: auto;
    max-height: 98px;
    max-width: 98px;
`;

const TextInformation = styled.div`
    grid-area: text;
    max-width: 100%;
`;

const Name = styled.h1`
    color: ${getColor('grey', 140)};
    font-size: ${getFontSize('big')};
    font-weight: bold;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
`;

const Author = styled.h3`
    color: ${getColor('grey', 120)};
    font-size: ${getFontSize('big')};
    font-weight: normal;
    margin: 0;
    margin-bottom: 5px;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
`;

const Tag = styled.span`
    color: ${getColor('grey', 120)};
    font-size: ${getFontSize('small')};
    text-transform: uppercase;
    font-weight: normal;

    border: 1px ${getColor('grey', 120)} solid;
    background: ${getColor('white')};
    border-radius: 2px;

    display: inline-block;
    line-height: ${getFontSize('small')};
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    padding: 2px 5px;
    margin-right: 5px;
`;

const Actions = styled.div`
    grid-area: actions;
    justify-self: end;
    align-self: end;

    & > * {
        margin-left: 10px;
    }
`;

type Props = {
    item: ConnectedApp;
};

const ConnectedAppCard: FC<Props> = ({item}) => {
    const translate = useTranslate();

    return (
        <CardContainer>
            <LogoContainer>
                <Logo src={item.logo} alt={item.name} />
            </LogoContainer>
            <TextInformation>
                <Name>{item.name}</Name>
                <Author>
                    {translate('akeneo_connectivity.connection.connect.connected_apps.list.card.developed_by')}
                    &nbsp;
                    {item.author}
                </Author>
                {item.categories.length > 0 && <Tag>{item.categories[0]}</Tag>}
            </TextInformation>
            <Actions>
                <Button ghost level='tertiary' href='#'>
                    {translate('akeneo_connectivity.connection.connect.connected_apps.list.card.manage_app')}
                </Button>
            </Actions>
        </CardContainer>
    );
};

export {ConnectedAppCard, Grid};