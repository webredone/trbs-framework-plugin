const { __ } = wp.i18n
const { DateTimePicker, Card, CardHeader, CardBody } = wp.components

import trUpdateField from '../../helpers/trUpdateField.js'
import TrTooltip from '../TrTooltip.js'

const trDateTime = ({ fieldData }) => {
  const { field_object, meta } = fieldData

  const handleChange = (fieldNewValue) => {
    const newValue = { ...field_object }
    newValue.value = fieldNewValue
    trUpdateField(fieldData, newValue)
  }

  return (
    <Card
      size="small"
      className={`tr-control tr-datetime-control tr-control-name--${meta.field_name}`}
    >
      <CardHeader size="small">
        {__(meta.label)}
        {meta.help && <TrTooltip help tooltip={meta.help} />}
      </CardHeader>
      <CardBody>
        <DateTimePicker
          currentDate={field_object.value}
          is12Hour={true}
          onChange={handleChange}
          __nextRemoveHelpButton
          __nextRemoveResetButton
        />
      </CardBody>
    </Card>
  )
}

export default trDateTime
