import { styled } from '@edtr-io/editor-ui'
import { Icon } from '@edtr-io/ui'
import { faBookOpen } from '@fortawesome/free-solid-svg-icons/faBookOpen'
import { faChessRook } from '@fortawesome/free-solid-svg-icons/faChessRook'
import { faCommentDots } from '@fortawesome/free-solid-svg-icons/faCommentDots'
import { faPencilRuler } from '@fortawesome/free-solid-svg-icons/faPencilRuler'
import { faSearchPlus } from '@fortawesome/free-solid-svg-icons/faSearchPlus'
import * as React from 'react'
import { faEdit } from '@fortawesome/free-solid-svg-icons/faEdit'

export const Buttoncontainer = styled.div({
  display: 'flex',
  flexDirection: 'row',
  width: '100%'
})

export const Container = styled.div({
  display: 'flex',
  flexDirection: 'row',
  flexWrap: 'wrap',
  position: 'relative'
})

export const ContentComponent = styled.div<{
  isHalf?: boolean
  boxfree?: boolean
}>(({ isHalf, boxfree }) => {
  return {
    marginTop: '10px',
    boxShadow: boxfree ? undefined : '0 1px 3px 0 rgba(0,0,0,0.2)',
    padding: '10px',
    width: isHalf ? '50%' : '100%',
    position: 'relative'
  }
})

export const BackgroundSymbol = styled.div({
  position: 'absolute',
  top: '0',
  right: '0',
  color: 'rgba(0,0,0,0.1)',
  transform: 'translate(-15px, 10px)',
  zIndex: 0
})
export enum SemanticPluginTypes {
  introduction,
  strategy,
  explanation,
  step,
  additionals,
  exercise
}

export function getIcon(type: SemanticPluginTypes, size: any) {
  switch (type) {
    case SemanticPluginTypes.introduction:
      return <Icon icon={faBookOpen} size={size} />
    case SemanticPluginTypes.strategy:
      return <Icon icon={faChessRook} size={size} />
    case SemanticPluginTypes.explanation:
      return <Icon icon={faCommentDots} size={size} />
    case SemanticPluginTypes.step:
      return <Icon icon={faPencilRuler} size={size} />
    case SemanticPluginTypes.additionals:
      return <Icon icon={faSearchPlus} size={size} />
    case SemanticPluginTypes.exercise:
      return <Icon icon={faEdit} size={size} />
  }
}
export function Content(
  props: React.PropsWithChildren<{
    type: SemanticPluginTypes
    isHalf?: boolean
    boxfree?: boolean
  }>
) {
  return (
    <React.Fragment>
      <ContentComponent isHalf={props.isHalf} boxfree={props.boxfree}>
        {props.children}
        <BackgroundSymbol>{getIcon(props.type, '3x')}</BackgroundSymbol>
      </ContentComponent>
    </React.Fragment>
  )
}

export const Controls = styled.div<{ show?: boolean }>(({ show }) => {
  return {
    right: '0',
    position: 'absolute',
    top: '0',
    transform: 'translate(50%, -5px)',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    visibility: show ? 'visible' : 'hidden'
  }
})

export const ControlButton = styled.button({
  borderRadius: '50%',
  border: '1px solid #858585',
  width: '25px',
  height: '25px',
  outline: 'none',
  textAlign: 'center',
  verticalAlign: 'middle',
  background: '#858585',
  color: 'white',
  zIndex: 20,
  '&:hover': {
    background: '#469bff',
    border: '1px solid #469bff'
  }
})

export const DragHandler = styled.div({
  borderRadius: '50%',
  padding: '0 2px 3px 2px',
  width: '25px',
  height: '25px',
  outline: 'none',
  textAlign: 'center',
  background: '#858585',
  color: 'white',
  zIndex: 20,
  '&:hover': {
    background: '#469bff'
  }
})